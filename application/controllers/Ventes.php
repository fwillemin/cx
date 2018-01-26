<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Ventes extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->view_folder = strtolower(__CLASS__) . '/';
        /* Connexion */
        if (!$this->ion_auth->logged_in() || !$this->session->userdata('loggedPdvId')) :
            redirect('secure/login');
        endif;
    }

    public function index() {
        redirect('ventes/bdcListe');
        exit;
    }

    public function getAllBdc($livres = 0) {
        switch ($livres) :
            case 0:
                /* Bdc non livrés */
                echo json_encode($this->managerBdc->listeListing(array('bdcEtat <' => 2)));
                break;
            case 1:
                /* Bdc livrés depuis moins de 6 mois */
                echo json_encode($this->managerBdc->listeListing(array('bdcEtat' => 2, 'bdcDate >' => (time() - 15768000))));
                break;
            case 2:
                /* Tous les Bdc livrés */
                echo json_encode($this->managerBdc->listeListing(array('bdcEtat' => 2)));
                break;
        endswitch;
    }

    /**
     * Réinitialisation de la vente et retour à un devis vierge
     */
    public function resetDevisEncours() {
        $this->venteInit();
        redirect('chiffrages/devis');
        exit;
    }

    public function addCartArticle() {

        if (!$this->form_validation->run('addCartArticle')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        /* Une quantité à 0 est refusée */
        elseif ($this->input->post('addArticleQte') <= 0) :
            echo json_encode(array('type' => 'error', 'message' => 'La quantité ne peux pas être inférieure ou égale à 0'));
            exit;
        endif;

        if (!$this->input->post('addArticleProduitId')) :
            /* Article hors catalogue */
            $id = 'Unique' . time();
            $tauxMarge = 'NA';
        else :
            $id = $this->input->post('addArticleProduitId');
            $tauxMarge = $this->ligneMarge($id, $this->input->post('addArticleQte'), $this->input->post('addArticlePrixUnitaire'), $this->input->post('addArticleRemise'));
        endif;

        $this->ajouteArticleAuPanier($this->input->post('addArticleRowid'), $id, $this->input->post('addArticleQte'), round($this->input->post('addArticlePrixUnitaire') * (1 - $this->input->post('addArticleRemise') / 100), 2), $this->input->post('addArticleDesignation'), $this->input->post('addArticleRemise'), array(
            'bdcArticleId' => $this->cart->get_item($this->input->post('addArticleRowid'))['options']['bdcArticleId'], /* ID de l'article dans la BDD[bdcarticles] */
            'produitId' => $this->input->post('addArticleProduitId'),
            'uniteId' => $this->input->post('addArticleUniteId'),
            'tauxMarge' => $tauxMarge,
            'tauxTVA' => number_format($this->input->post('addArticleTauxTVA'), 2, '.', ''),
            'prixUnitaire' => $this->input->post('addArticlePrixUnitaire'), /* Prix avant remise */
            'approvisionnement' => array(
//                        'action' => $a->getArticleAction(),
//                        'approId' => $a->getArticleApproId(),
//                        'commandeId' => $a->getArticleCommandeAppro() ? $a->getArticleCommandeAppro()->getCommandeId() : 0,
//                        'commandeEtat' => $a->getArticleCommandeAppro() ? $a->getArticleCommandeAppro()->getCommandeEtat() : 0
            ),
            'resteALivrer' => $this->cart->get_item($this->input->post('addArticleRowid'))['options']['resteALivrer'],
            'qteLivree' => $this->cart->get_item($this->input->post('addArticleRowid'))['options']['qteLivree']
                )
        );

        /* on remet à jour le poids du panier, totalTTC et totalTVA */
        $this->getPoidsCart();
        $this->getTotauxTaxes();

        echo json_encode(array('type' => 'success'));
        exit;
    }

    public function reloadBdc($bdcId = null) {
        if (!$bdcId || !$this->existBdc($bdcId)) :
            redirect('ventes/bdcListe');
            exit;
        endif;

        $this->venteInit();
        $bdc = $this->managerBdc->getBdcById($bdcId);

        $this->cart->destroy();
        /* on change la session */
        $session = array(
            'venteType' => 2,
            'venteDevisId' => $bdc->getBdcDevisId(),
            'venteClientId' => $bdc->getBdcClientId(),
            'venteClientType' => $bdc->getBdcClient()->getClientType(),
            'venteDate' => $bdc->getBdcDate(),
            'venteCommentaire' => $bdc->getBdcCommentaire(),
            'venteId' => $bdc->getBdcId(),
            'venteEtat' => $bdc->getBdcEtat(),
            'ventePoids' => $bdc->getBdcPoids(),
            'venteEditable' => true,
            'venteCollaborateurId' => $bdc->getBdcCollaborateurId(),
            'venteCollaborateur' => $bdc->getBdcCollaborateur()->getCollaborateurNom(),
            'venteExonerationTVA' => $bdc->getBdcClient()->getClientExonerationTVA()
        );
        $this->session->set_userdata($session);

        $bdc->hydrateArticles();
        if ($bdc->getBdcArticles()) :
            $i = 0;
            foreach ($bdc->getBdcArticles() as $a) :
                $i++;
                if ($a->getArticleProduitId() == 0) :
                    $id = 'Unique' . $i;
                    $tauxMarge = 'NA';
                else :
                    $id = $a->getArticleProduitId();
                    $tauxMarge = $this->ligneMarge($id, $a->getArticleQte(), $a->getArticlePrixUnitaire(), $a->getArticleRemise());
                endif;

                $this->ajouteArticleAuPanier(null, $id, $a->getArticleQte(), $a->getArticlePrixNet(), $a->getArticleDesignation(), $a->getArticleRemise(), array(
                    'bdcArticleId' => $a->getArticleId(), /* ID de l'article dans la BDD[bdcarticles] */
                    'produitId' => $a->getArticleProduitId(),
                    'uniteId' => $a->getArticleUniteId(),
                    'tauxMarge' => $tauxMarge,
                    'tauxTVA' => $a->getArticleTauxTVA(),
                    'prixUnitaire' => $a->getArticlePrixUnitaire(), /* Prix avant remise */
                    'approvisionnement' => array(
//                        'action' => $a->getArticleAction(),
//                        'approId' => $a->getArticleApproId(),
//                        'commandeId' => $a->getArticleCommandeAppro() ? $a->getArticleCommandeAppro()->getCommandeId() : 0,
//                        'commandeEtat' => $a->getArticleCommandeAppro() ? $a->getArticleCommandeAppro()->getCommandeEtat() : 0
                    ),
                    'resteALivrer' => ( $a->getArticleQte() - $a->getArticleQteLivree() ),
                    'qteLivree' => $a->getArticleQteLivree()
                        )
                );

            endforeach;
        endif;
        $this->getTotauxTaxes();
        redirect('ventes/bdc');
    }

    public function bdc() {

        if (!$this->session->userdata('venteDevisId')) :
            redirect('ventes/bdcListe');
            exit;
        endif;

        if ($this->session->userdata('venteId') && intval($this->session->userdata('venteId')) > 0) :
            $bdc = $this->managerBdc->getBdcById(intval($this->session->userdata('venteId')));
            $bdc->hydrateArticles();
            $bls = $this->managerBls->getBlByBdcId($bdc->getBdcId());
            if (!empty($bls)) :
                foreach ($bls as $b) :
                    $b->hydrateFacture();
                endforeach;
            endif;

            $factures = $this->managerFactures->getFacturesByBdcId($bdc->getBdcId());
            $reglements = $this->managerReglements->getReglementsByBdcId($bdc->getBdcId());
            if ($reglements):
                foreach ($reglements as $r):
                    $r->hydrateHistorique();
                endforeach;
            endif;

        else :
            $bdc = $stocks = $livraisons = $bls = $factures = $faturesDelete = $reglements = $acomptes = array();
        endif;

        if ($this->session->userdata('venteClientId')) :
            $client = $this->managerClients->getClientById($this->session->userdata('venteClientId'));
        else :
            $client = array();
        endif;


        $data = array(
            'client' => $client,
            'collaborateurs' => $this->managerCollaborateurs->liste(array('collaborateurActive' => 1)),
            'bdc' => $bdc,
            'bls' => $bls,
            'factures' => $factures,
            //'facturesDelete' => $faturesDelete,
            'reglements' => $reglements,
            //'acomptes' => $acomptes,
            'title' => 'CX - Module  de génération des bons de commande.',
            'description' => '',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function bdcListe() {

        $this->venteInit();
        $this->session->set_userdata('venteType', 2);
        $bdcs = $this->managerBdc->liste(array('bdcEtat <' => 2)); /* on ne liste pas les Bdc livrés */
        if (!empty($bdcs)) :
            foreach ($bdcs as $b) :
                $b->setAvancement();
            endforeach;
        endif;

        $data = array(
            'bdc' => $bdcs,
            //'bls' => $this->m_bl->liste(),
            'title' => 'CX - Module  de gestion des bons de commande.',
            'description' => '',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    /**
     *  permet de modifier le montant de l'acompte ou le commentaire d'un Bdc
     */
    public function venteChange() {
        $this->form_validation->set_rules('venteOption', 'Option a modifier', 'required|trim');
        $this->form_validation->set_rules('venteValeur', 'valeur de l\'option', 'trim');
        if ($this->form_validation->run()) :
            $this->session->set_userdata('venteCommentaire', $this->input->post('venteValeur'));
            echo json_encode(array('type' => 'success'));
            exit;
        else :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
    }

    /**
     * Enregistre un article du panier dans la base de données pour un BDC
     * @param array $item Une ligne du panier.
     * @param int $bdcId ID du bdc auquel enregistrer l'article
     */
    private function saveNewArticleBdc($item, $bdcId) {
        if (substr($item['id'], 0, 6) == 'Unique') :
            $id = null;
        else :
            $id = $item['id'];
        endif;

        $articleHT = round($item['price'] * $item['qty'], 2);

        $dataArticle = array(
            'articleBdcId' => $bdcId,
            'articleProduitId' => $id,
            'articleDesignation' => $item['name'],
            'articleQte' => $item['qty'],
            'articlePrixUnitaire' => $item['options']['prixUnitaire'],
            'articleRemise' => $item['remise'],
            'articlePrixNet' => $item['price'],
            'articleUniteId' => $item['options']['uniteId'],
            'articleTotalHT' => $articleHT,
            'articleTauxTVA' => $item['options']['tauxTVA'],
            'articleAction' => 0,
            'articleApproId' => null,
            'articleQteLivree' => 0,
            'articleDelete' => 0
        );

        $article = new Bdcarticle($dataArticle);
        $this->managerBdcarticles->ajouter($article);
        return $article;
    }

    public function addBdc() {
        $valide = true;
        if (!$this->cart->contents()) :
            log_message('error', 'Impossible d\'ajouter un bdc : panier vide');
            $valide = false;
        endif;

        if ($valide == false) :
            echo json_encode(array('type' => 'error', 'message' => 'Bdc invalide'));
            exit;
        endif;

        /* Calcul de la TVA totale */
        $tva = 0;
        foreach ($this->session->userdata('venteTVA') as $taux => $montant) :
            if ($taux > 0) :
                $tva += $montant;
            endif;
        endforeach;

        if ($this->session->userdata('venteId')) :
            /* Modification du BDC */
            if (!$this->existBdc($this->session->userdata('venteId'))):
                echo json_encode(array('type' => 'error', 'message' => 'BDC introuvable'));
                exit;
            endif;

            $bdc = $this->managerBdc->getBdcById(intval($this->session->userdata('venteId')));
            $bdc->setBdcCollaborateurId($this->session->userdata('venteCollaborateurId'));
            $bdc->setBdcDate(intval($this->session->userdata('venteDate')));
            $bdc->setBdcClientId(intval($this->session->userdata('venteClientId')));
            $bdc->setBdcNbArticles($this->cart->total_items());
            $bdc->setBdcTotalHT($this->cart->total());
            $bdc->setBdcTotalTTC($this->session->userdata('venteTTC'));
            $bdc->setBdcTotalTVA($tva);
            $bdc->setBdcPoids($this->session->userdata('ventePoids'));
            $bdc->setBdcCommentaire($this->session->userdata('venteCommentaire'));

            $this->managerBdc->editer($bdc);
            $this->managerBdctva->deleteTvaByBdcId($bdc->getBdcId());

        else :
            /* Création du bdc */
            $dataBdc = array(
                'bdcPdvId' => $this->session->userdata('loggedPdvId'),
                'bdcDateCreation' => time(),
                'bdcDate' => intval($this->session->userdata('venteDate')),
                'bdcDevisId' => intval($this->session->userdata('venteDevisId')),
                'bdcClientId' => intval($this->session->userdata('venteClientId')),
                'bdcNbArticles' => $this->cart->total_items(),
                'bdcTotalHT' => $this->cart->total(),
                'bdcTotalTVA' => $tva,
                'bdcTotalTTC' => $this->session->userdata('venteTTC'),
                'bdcPoids' => $this->session->userdata('ventePoids'),
                'bdcCollaborateurId' => $this->session->userdata('venteCollaborateurId'),
                'bdcEtat' => 0,
                'bdcCommentaire' => $this->session->userdata('venteCommentaire'),
                'bdcDelete' => 0
            );
            $bdc = new Bdc($dataBdc);
            $this->managerBdc->ajouter($bdc);

            /* Modification de l'etat du devis */
            $devis = $this->managerDevis->getDevisById($this->session->userdata('venteDevisId'));
            $devis->setDevisEtat(1);
            $this->managerDevis->editer($devis);
        endif;

        /* On enregistre les TVA pour le Bdc */
        if ($this->session->userdata('venteExonerationTVA') == 0) :
            foreach ($this->session->userdata('venteTVA') as $taux => $montant) :
                $dataTVA = array('tvaBdcId' => $bdc->getBdcId(), 'tvaTaux' => $taux, 'tvaMontant' => $montant);
                $tvaBdc = new Bdctva($dataTVA);
                $this->managerBdctva->ajouter($tvaBdc);
            endforeach;
        endif;

        /* Ajout des articles au bdc */
        $newArticles = array();
        $bdc->hydrateArticles();
        foreach ($this->cart->contents() as $item) :
            if ($item['options']['bdcArticleId']):
                $article = $this->managerBdcarticles->getArticleById($item['options']['bdcArticleId']);

                $article->setArticleDesignation($item['name']);
                $article->setArticleQte($item['qty']);
                $article->setArticlePrixUnitaire($item['options']['prixUnitaire']);
                $article->setArticleRemise($item['remise']);
                $article->setArticleUniteId($item['options']['uniteId']);
                $article->setArticlePrixNet($item['price']);

                $this->managerBdcarticles->editer($article);
            else:
                $article = $this->saveNewArticleBdc($item, $bdc->getBdcId());
            endif;
            $newArticles[] = $article->getArticleId();

        endforeach;

        /* Suppression des articles de la vente qui auraient été supprimés */
        if ($bdc->getBdcArticles()):
            foreach ($bdc->getBdcArticles() as $a):
                if (!in_array($a->getArticleId(), $newArticles)):
                    $this->managerBdcarticles->delete($a);
                endif;
            endforeach;
        endif;

        echo json_encode(array('type' => 'success', 'bdc' => $bdc->getBdcId()));
        exit;
    }

    public function deleteBdc() {

        if (!$this->form_validation->run('getBdc')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        $bdc = $this->managerBdc->getBdcById($this->input->post('bdcId'));
        if ($bdc->getBdcEtat() > 0) :
            echo json_encode(array('type' => 'error', 'message' => 'Le bon de commande est livré, suppression impossible.'));
            exit;
        else :

            $bdc->setBdcDelete(1);
            $this->managerBdc->editer($bdc);

            echo json_encode(array('type' => 'success'));
            exit;
        endif;
    }

    public function reanimateBdc() {

        if (!$this->form_validation->run('getBdc')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        $bdc = $this->managerBdc->getBdcById($this->input->post('bdcId'));
        $bdc->setBdcDelete(0);
        $this->managerBdc->editer($bdc);

        echo json_encode(array('type' => 'success'));
        exit;
    }

    public function sendBdcByEmail() {

        if (!$this->existBdc($this->session->userdata('venteId'))):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        $pdv = $this->managerPdv->getPdvById($this->session->userdata('loggedPdvId'));
        $bdc = $this->managerBdc->getBdcById($this->session->userdata('venteId'));
        if (!$bdc->getBdcClient()->getClientEmail() || !valid_email($bdc->getBdcClient()->getClientEmail())):
            echo json_encode(array('type' => 'error', 'message' => 'Le client n\'a pas d\'email renseigné ou cet email est invalide.'));
            exit;
        endif;

        if ($this->own->emailBdc($bdc, $pdv)):
            echo json_encode(array('type' => 'success'));
        else:
            echo json_encode(array('type' => 'error', 'message' => 'Erreur lors de l\'envoi de l\'email'));
        endif;
        exit;
    }

}
