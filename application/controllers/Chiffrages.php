<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Chiffrages extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->view_folder = strtolower(__CLASS__) . '/';
        /* Connexion */
        if (!$this->ion_auth->logged_in() || !$this->session->userdata('loggedPdvId')) :
            redirect('secure/login');
        endif;
    }

    public function index() {
        redirect('chiffrages/devis');
        exit;
    }

    public function devisListe() {

        $this->venteInit();
        $this->session->set_userdata('venteType', 1);
        $devis = $this->managerDevis->listeNonConvertis();

        $data = array(
            'devis' => $devis,
            'title' => 'Liste des devis.',
            'description' => '',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function getAllDevis() {
        echo json_encode($this->managerDevis->listeNonConvertis(array('devisDate >' => time() - 31536000), 'array'));
    }

    public function devis() {

        /* on charge d'eventuelles données de vente enregistrées en session */
        if (!$this->session->userdata('venteType') || $this->session->userdata('venteType') > 1) :
            $this->venteInit();
            $this->session->set_userdata(array('venteType' => 1, 'venteDate' => time(), 'venteEditable' => true, 'venteEtat' => 0));
        endif;

        if ($this->session->userdata('venteClientId')) :
            $client = $this->managerClients->getClientById($this->session->userdata('venteClientId'));
        else :
            $client = array();
        endif;

        $data = array(
            'client' => $client,
            'collaborateurs' => $this->managerCollaborateurs->liste(array('collaborateurActive' => 1)),
            'title' => 'Devis',
            'description' => '',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function addCartArticle() {

        if (!$this->form_validation->run('addCartArticle')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        /* Une quantité à 0 est refusée pour un nouvel aticle et est considéré comme une suppression pour un article existant */
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
            'produitId' => $this->input->post('addArticleProduitId'),
            'tauxMarge' => $tauxMarge,
            'uniteId' => $this->input->post('addArticleUniteId'),
            'tauxTVA' => number_format($this->input->post('addArticleTauxTVA'), 2, '.', ''),
            'prixUnitaire' => $this->input->post('addArticlePrixUnitaire'),
            'devisArticleId' => $this->cart->get_item($this->input->post('addArticleRowid'))['options']['devisArticleId']
                )
        );

        /* on remet à jour le poids du panier, totalTTC et totalTVA */
        $this->getPoidsCart();
        $this->getTotauxTaxes();

        echo json_encode(array('type' => 'success'));
        exit;
    }

    /**
     * Réinitialisation de la vente et retour à un devis vierge
     */
    public function resetDevisEncours() {
        $this->venteInit();
        redirect('chiffrages/devis');
        exit;
    }

    /**
     * Ajoute le devis en session à la bdd
     */
    public function addDevis() {

        $valide = true;
        if (!$this->cart->contents()) :
            //log_message('error', 'Impossible d\'ajouter un devis : panier vide');
            $message = 'Votre devis ne contient aucun article.';
            $valide = false;
        endif;
        if (!$this->session->userdata('venteClientId') || $this->session->userdata('venteClientId') <= 0) :
            //log_message('error', 'Impossible d\'ajouter une vente : aucun client sélectionné.');
            $message = 'Vous devez selectionner un client';
            $valide = false;
        endif;
        if (!$this->session->userdata('venteCollaborateurId') || $this->session->userdata('venteCollaborateurId') <= 0) :
            //log_message('error', 'Impossible d\'ajouter une vente : veuillez sélectionner un vendeur.');
            $message = 'Vous devez selectionner un vendeur';
            $valide = false;
        endif;
        if (!$this->session->userdata('venteType') || $this->session->userdata('venteType') != 1) :
            //log_message('error', 'Impossible d\'ajouter un devis : le type de vente est à BDC.');
            $message = 'La vente en cours est invalide. Retournez à l\'accueil et recommencez !';
            $valide = false;
        endif;

        if ($valide == false) :
            echo json_encode(array('type' => 'error', 'message' => $message));
            exit;
        endif;

        /* Calcul de la TVA totale */
        $tva = 0;
        foreach ($this->session->userdata('venteTVA') as $taux => $montant) :
            if ($taux > 0) :
                $tva += $montant;
            endif;
        endforeach;

        $this->db->trans_start();

        if ($this->session->userdata('venteId')) :
            /* Modification d'un devis */
            $devis = $this->managerDevis->getDevisById(intval($this->session->userdata('venteId')));
            if (empty($devis)) :
                echo json_encode(array('type' => 'error', 'message' => 'Devis introuvable'));
                exit;
            else :
                $devis->setDevisCollaborateurId($this->session->userdata('venteCollaborateurId'));
                $devis->setDevisDate(intval($this->session->userdata('venteDate')));
                $devis->setDevisClientId(intval($this->session->userdata('venteClientId')));
                $devis->setDevisNbArticles($this->cart->total_items());
                $devis->setDevisTotalHT($this->cart->total());
                $devis->setDevisTotalTTC($this->session->userdata('venteTTC'));
                $devis->setDevisTotalTVA($tva);
                $devis->setDevisPoids($this->session->userdata('ventePoids'));

                $this->managerDevis->editer($devis);
            endif;
        else :
            /* Création d'un devis */
            $dataDevis = array(
                'devisPdvId' => $this->session->userdata('loggedPdvId'),
                'devisDateCreation' => time(),
                'devisDate' => intval($this->session->userdata('venteDate')),
                'devisClientId' => intval($this->session->userdata('venteClientId')),
                'devisNbArticles' => $this->cart->total_items(),
                'devisTotalHT' => $this->cart->total(),
                'devisTotalTVA' => $tva,
                'devisTotalTTC' => $this->session->userdata('venteTTC'),
                'devisPoids' => $this->session->userdata('ventePoids'),
                'devisCollaborateurId' => $this->session->userdata('venteCollaborateurId'),
                'devisEtat' => 0,
                'devisDelete' => 0
            );
            $devis = new Devis($dataDevis);
            $this->managerDevis->ajouter($devis);
            $this->session->set_userdata('venteId', $devis->getDevisId());
        endif;

        /* on ajoute les articles de la vente à la bdd */
        $devis->hydrateArticles();
        $newArticles = array();
        foreach ($this->cart->contents() as $item) :

            if (substr($item['id'], 0, 6) == 'Unique') :
                $id = null;
            else :
                $id = $item['id'];
            endif;
            $articleHT = round($item['price'] * $item['qty'], 2);

            if ($item['options']['devisArticleId']):
                /* Modification d'un article de devis existant */
                $newArticles[] = $item['options']['devisArticleId'];
                $article = $this->managerDevisarticles->getArticleById($item['options']['devisArticleId']);
                $article->setArticleProduitId($id);
                $article->setArticleDesignation($item['name']);
                $article->setArticleQte($item['qty']);
                $article->setArticlePrixUnitaire($item['options']['prixUnitaire']);
                $article->setArticleUniteId($item['options']['uniteId']);
                $article->setArticleRemise($item['remise']);
                $article->setArticlePrixNet($item['price']);
                $article->setArticleTotalHT($articleHT);
                $article->setArticleTauxTVA($item['options']['tauxTVA']);
                $this->managerDevisarticles->editer($article);

            else:
                /* Nouvel Article pour ce devis */
                $dataArticle = array(
                    'articleDevisId' => $devis->getDevisId(),
                    'articleProduitId' => $id,
                    'articleDesignation' => $item['name'],
                    'articleQte' => $item['qty'],
                    'articlePrixUnitaire' => $item['options']['prixUnitaire'],
                    'articleRemise' => $item['remise'],
                    'articlePrixNet' => $item['price'],
                    'articleUniteId' => $item['options']['uniteId'],
                    'articleTotalHT' => $articleHT,
                    'articleTauxTVA' => $item['options']['tauxTVA']
                );
                $article = new Devisarticle($dataArticle);
                $this->managerDevisarticles->ajouter($article);
                $newArticles[] = $article->getArticleDevisId();

            endif;

            unset($article);

        endforeach;

        /* Suppression des articles du devis qui auraient été supprimés */
        if ($devis->getDevisArticles()):
            foreach ($devis->getDevisArticles() as $a):
                if (!in_array($a->getArticleId(), $newArticles)):
                    $this->managerDevisarticles->delete($a);
                endif;
            endforeach;
        endif;

        $this->db->trans_complete();

        echo json_encode(array('type' => 'success'));
        exit;
    }

    private function setDevisToSession(Devis $devis, $dupliquer = false) {

        $editable = true;
        /* Si le devis à un BDC associé, on ne peux plus le modifier */
        if ($devis->getDevisBdcId() != 0) :
            $editable = false;
        endif;

        $this->cart->destroy();
        $client = $this->managerClients->getClientById($devis->getDevisClientId());
        $session = array(
            'venteDate' => $dupliquer ? time() : $devis->getDevisDate(),
            'venteType' => 1,
            'venteEtat' => $dupliquer ? 0 : $devis->getDevisEtat(),
            'venteClientId' => $client->getClientId(),
            'venteClientType' => $devis->getDevisClient()->getClientType(),
            'venteId' => $dupliquer ? '' : $devis->getDevisId(),
            'venteBdcId' => $dupliquer ? '' : $devis->getDevisBdcId(),
            'ventePoids' => $devis->getDevisPoids(),
            'venteEditable' => $dupliquer ? true : $editable,
            'venteCollaborateurId' => $devis->getDevisCollaborateurId(),
            'venteCollaborateur' => $devis->getDevisCollaborateur()->getCollaborateurNom(),
            'venteExonerationTVA' => $client->getClientExonerationTVA()
        );
        $this->session->set_userdata($session);

        /* on recharge les articles du devis */
        $devis->hydrateArticles();
        $i = 0;
        foreach ($devis->getDevisArticles() as $a) :
            $i++;
            if ($a->getArticleProduitId() == 0) :
                $id = 'Unique' . $i;
                $tauxMarge = 'NA';
            else :
                $id = $a->getArticleProduitId();
                $tauxMarge = $this->ligneMarge($id, $a->getArticleQte(), $a->getArticlePrixUnitaire(), $a->getArticleRemise());
            endif;

            $this->ajouteArticleAuPanier(null, $id, $a->getArticleQte(), $a->getArticlePrixNet(), $a->getArticleDesignation(), $a->getArticleRemise(), array(
                'produitId' => $a->getArticleProduitId(),
                'uniteId' => $a->getArticleUniteId(),
                'tauxMarge' => $tauxMarge,
                'tauxTVA' => $a->getArticleTauxTVA(),
                'prixUnitaire' => $a->getArticlePrixUnitaire(), /* Prix avant remise */
                'devisArticleId' => $dupliquer ? '' : $a->getArticleId()
                    )
            );
        endforeach;

        $this->getTotauxTaxes();
        $this->getPoidsCart();
    }

    public function reloadDevis($devisId = null) {

        if (!$devisId || !$this->existDevis($devisId)) :
            redirect('chiffrages');
            exit;
        endif;

        /* recherche du devis */
        $devis = $this->managerDevis->getDevisById($devisId);
        $this->setDevisToSession($devis);

        redirect('chiffrages/devis/');
        exit;
    }

    public function deleteDevis($devisId = null) {

        if (!$devisId || !$this->existDevis($devisId)):
            redirect('chiffrages/reloadDevis/' . $devisId);
            exit;
        endif;
        $devis = $this->managerDevis->getDevisById($devisId);
        if (!$devis->getDevisBdcId()):
            $this->delDevis($devis);
        endif;
        redirect('chiffrages/devisListe');
        exit;
    }

    public function dupliquerDevis($devisId = null) {
        if (!$devisId || !$this->existDevis($devisId)):
            redirect('chiffrages/reloadDevis/' . $devisId);
        endif;
        /* recherche du devis */
        $devis = $this->managerDevis->getDevisById($devisId);
        $this->setDevisToSession($devis, true);

        redirect('chiffrages/devis/');
        exit;
    }

    public function devisPerdu($devisId) {
        if ($this->existDevis($devisId)):
            $devis = $this->managerDevis->getDevisById($devisId);
            $devis->setDevisEtat(1);
            $this->managerDevis->editer($devis);
            redirect('chiffrages/reloadDevis/' . $devisId);
        else:
            redirect('chiffrages/devisListe');
        endif;
        exit;
    }

    /**
     * Repasse un devis perdu en Encours
     * @param int $devisId
     */
    public function devisEncours($devisId) {
        if ($this->existDevis($devisId)):
            $devis = $this->managerDevis->getDevisById($devisId);
            $devis->setDevisEtat(0);
            $this->managerDevis->editer($devis);
            redirect('chiffrages/reloadDevis/' . $devisId);
        else:
            redirect('chiffrages/devisListe');
        endif;
        exit;
    }

    public function generationBdc() {
        if ($this->session->userdata('venteId') && $this->session->userdata('venteType') == 1) :
            $devis = $this->managerDevis->getDevisById($this->session->userdata('venteId'));
            if (empty($devis)) :
                redirect('chiffrages/devis');
                exit;
            else :
                if ($devis->getDevisBdcId() > 0) :
                    log_message('error', 'Tentative de génération d\'un bdc déjà existant');
                    redirect('chiffrages/devis');
                    exit;
                else :
                    /* on change la session */
                    $session = array(
                        'venteId' => '',
                        'venteEtat' => '0',
                        'venteType' => 2,
                        'venteDevisId' => $devis->getDevisId(),
                        'venteDate' => time(),
                        'venteAcompte' => 0,
                        'venteCommentaire' => '',
                        'venteCollaborateurId' => $devis->getDevisCollaborateurId(),
                        'venteCollaborateur' => $devis->getDevisCollaborateur()->getCollaborateurNom()
                    );
                    $this->session->set_userdata($session);
                    $this->session->unset_userdata(array('venteId' => '', 'venteBdcId' => '')); /* on supprime l'id de vente qui correpondait à celui du devis */
                    /* on conserve dans la session : venteClientId, ventePoids */
                    /* on conserve le cart en modifiant une option */
                    foreach ($this->cart->contents() as $item):
                        $item['options']['bdcArticleId'] = null;
                        unset($item['options']['devisArticleId']);
                        $this->cart->update($item);
                    endforeach;
                    redirect('ventes/bdc');
                    exit;
                endif;
            endif;
        endif;
    }

    private function delDevis(Devis $devis) {
        $devis->setDevisDelete(1);
        $this->managerDevis->editer($devis);
    }

    public function purgerDevis() {

        if ($this->form_validation->run('purgeDevis')) :
            $devisAPurger = $this->managerDevis->listeNonConvertis(array('devisDate <' => $this->cxwork->mktimeFromInputDate($this->input->post('limitePurge'))));
            $nbSupprime = 0;
            foreach ($devisAPurger as $d) :
                $this->delDevis($d);
                $nbSupprime++;
            endforeach;
            echo json_encode(array('type' => 'success', 'nbSupprime' => $nbSupprime));
            exit;
        else :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
    }

    /**
     * Analyse des devis sur les 12 derniers mois
     */
    public function analyseDevis() {

        $data = array(
            'title' => 'Analyse des devis',
            'description' => '',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function sendDevisByEmail() {

        if (!$this->existDevis($this->session->userdata('venteId'))):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        $pdv = $this->managerPdv->getPdvById($this->session->userdata('loggedPdvId'));
        $devis = $this->managerDevis->getDevisById($this->session->userdata('venteId'));
        if (!$devis->getDevisClient()->getClientEmail() || !valid_email($devis->getDevisClient()->getClientEmail())):
            echo json_encode(array('type' => 'error', 'message' => 'Le client n\'a pas d\'email renseigné ou cet email est invalide.'));
            exit;
        endif;

        if ($this->own->emailDevis($devis, $pdv)):
            echo json_encode(array('type' => 'success'));
        else:
            echo json_encode(array('type' => 'error', 'message' => 'Erreur lors de l\'envoi de l\'email'));
        endif;
        exit;
    }

}
