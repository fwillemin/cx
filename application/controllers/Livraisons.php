<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Livraisons extends My_Controller {

    public function __construct() {
        parent::__construct();

        $this->view_folder = strtolower(__CLASS__) . '/';
        /* Connexion */
        if (!$this->ion_auth->logged_in() || !$this->session->userdata('loggedPdvId')) :
            redirect('secure/login');
        endif;
    }

    private function getBlTotauxTaxes(Bl $bl) {
        if (!empty($bl->getBlLivraisons())) :
            //$TTC = 0;
            $taxableHT = $TVA = array();

            foreach ($bl->getBlLivraisons() as $l) :

                $livraisonHT = round($l->getLivraisonArticle()->getArticlePrixNet() * $l->getLivraisonQte(), 2);

                if (isset($taxableHT[$l->getLivraisonArticle()->getArticleTauxTVA()])) :
                    $taxableHT[$l->getLivraisonArticle()->getArticleTauxTVA()] += $livraisonHT;
                else :
                    $taxableHT[$l->getLivraisonArticle()->getArticleTauxTVA()] = $livraisonHT;
                endif;

            endforeach;

            foreach ($taxableHT as $taux => $montant):
                $tva = round($montant * $taux / 100, 2);
                $TVA[$taux] = $tva;
                //$TTC += $tva + $montant;
            endforeach;

            return $TVA;
        else :
            return false;
        endif;
    }

    public function addBl() {

        if (!$this->form_validation->run('getBdc')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        $bdc = $this->managerBdc->getBdcbyId($this->input->post('bdcId'));

        $this->db->trans_start();

        /* on va créer un Bon de livraison */
        $arrayBl = array(
            'blPdvId' => $this->session->userdata('loggedPdvId'),
            'blBdcId' => $bdc->getBdcId(),
            'blDate' => time(),
            'blFactureId' => null,
            'blDelete' => 0
        );

        $bl = new Bl($arrayBl);
        $this->managerBls->ajouter($bl);

        foreach ($this->input->post('livraisons') as $l) :
            $article = $this->managerBdcarticles->getArticleById($l[0]);

            /* on gere le cas des articles hors stock sans multiple renseigné */
            if ($article->getArticleProduitId() > 0) :
                $multiple = $article->getArticleProduit()->getProduitMultiple();
            else :
                $multiple = 1;
            endif;

            /* on teste si la quantité livrée n'est pas supérieure à celle commandée pour chaque article */
            if ($article->getArticleQteLivree() + round($l[1] * $multiple, 2) > $article->getArticleQte()) :
                echo json_encode(array('type' => 'error', 'message' => 'La quantité de "' . $article->getArticleDesignation() . '" livrée est supérieure à celle commandée.'));
                exit;
            endif;

            $stock = false;
            if (!empty($l[2]) && !$this->existStock($l[2])):
                echo json_encode(array('type' => 'error', 'message' => 'Stock introuvable'));
                exit;
            endif;

            $qteLivree = $l[1] * $multiple;
            $arrayLivraison = array(
                'livraisonBlId' => $bl->getBlId(),
                'livraisonArticleId' => $article->getArticleId(),
                'livraisonStockId' => $stock ? $stock->getStockId() : null,
                'livraisonQte' => $qteLivree
            );
            $livraison = new Livraison($arrayLivraison);
            $this->managerLivraisons->ajouter($livraison);

            /* On met à jour le stock */
            if ($stock) :
                $stock->setStockQte($stock->getStockQte() - $qteLivree);
                $this->managerStocks->editer($stock);
            endif;

            /* Mise à jour de l'article du BDC */
            $article->setArticleQteLivree($article->getArticleQteLivree() + $qteLivree);
            $this->managerBdcarticles->editer($article);
            unset($article);
            unset($stock);

        endforeach;

        $blNew = $this->managerBls->getBlById($bl->getBlId());
        $blNew->hydrateLivraisons();


        /* Enregistrement des TVAs du Bon de Livraison */
        $tvas = $this->getBlTotauxTaxes($blNew);
        $this->managerBltva->deleteTvaByBlId($blNew->getBlId());

        if ($this->session->userdata('venteExonerationTVA') == 0) :
            if ($tvas) :
                foreach ($tvas as $taux => $montant) :
                    $dataTVA = array('tvaBlId' => $blNew->getBlId(), 'tvaTaux' => $taux, 'tvaMontant' => round($montant, 2));
                    $tvaBl = new Bltva($dataTVA);
                    $this->managerBltva->ajouter($tvaBl);
                endforeach;
            endif;
        endif;

        /* Rechargement du Bdc pour mettre à jour les attributs */
        $bdcNew = $this->managerBdc->getBdcById($bdc->getBdcId());
        $bdcNew->majEtat();
        $this->managerBdc->editer($bdcNew);

        $this->db->trans_complete();

        echo json_encode(array('type' => 'success'));
        exit;
    }

    public function delBl() {

        if (!$this->form_validation->run('getBl')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
        $bl = $this->managerBls->getBlById(intval($this->input->post('blId')));

        $bl->hydrateLivraisons();
        if (!empty($bl->getBlLivraisons())) :
            foreach ($bl->getBlLivraisons() as $l) :
                /* on modifie l'article du Bdc pour retirer la qte livrée */
                $article = $this->managerBdcarticles->getArticleById($l->getLivraisonArticleId());
                $article->setArticleQteLivree($article->getArticleQteLivree() - $l->getLivraisonQte());
                $this->managerBdcarticles->editer($article);

                /* on modifie également le stock du produit */
                if ($l->getLivraisonStockId() > 0) :
                    $stock = $this->managerStocks->getStockById($l->getLivraisonStockId());
                    /* le stock initialement débité est recrédite */
                    $stock->setStockQte($stock->getStockQte() + $l->getLivraisonQte());
                    $this->managerStocks->editer($stock);
                endif;
            endforeach;
        endif;

        /* on supprime le BL */
        $bl->setBlDelete(1);
        $this->managerBls->editer($bl);

        /* on recalcule l'état du Bdc */
        $bdc = $this->managerBdc->getBdcById($bl->getBlBdcId());
        $bdc->majEtat();
        $this->managerBdc->editer($bdc);

        echo json_encode(array('type' => 'success'));
        exit;
    }

    public function sendBlByEmail() {

        if (!$this->existBl($this->input->post('blId'))):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        $pdv = $this->managerPdv->getPdvById($this->session->userdata('loggedPdvId'));
        $bl = $this->managerBls->getBlById($this->input->post('blId'));
        $bl->hydrateClient();
        if (!$bl->getBlClient()->getClientEmail() || !valid_email($bl->getBlClient()->getClientEmail())):
            echo json_encode(array('type' => 'error', 'message' => 'Le client n\'a pas d\'email renseigné ou cet email est invalide.'));
            exit;
        endif;

        if ($this->own->emailBl($bl, $pdv)):
            echo json_encode(array('type' => 'success'));
        else:
            echo json_encode(array('type' => 'error', 'message' => 'Erreur lors de l\'envoi de l\'email'));
        endif;
        exit;
    }

}
