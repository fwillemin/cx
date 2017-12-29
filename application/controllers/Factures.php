<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Factures extends My_Controller {

    const tauxTVA = 20.00;

    public function __construct() {
        parent::__construct();

        $this->view_folder = strtolower(__CLASS__) . '/';
        /* Connexion */
        if (!$this->ion_auth->logged_in() || !$this->session->userdata('loggedPdvId')) :
            redirect('secure/login');
        endif;
    }

    public function addFacture() {

        $factureTvas = array();
        $factureTotalTVA = 0;

        /* Si on ne recoit aucun BL, on sort */
        if (empty($this->input->post('bls'))) :
            echo json_encode(array('type' => 'error', 'message' => 'Aucun Bon de livraison à facturer.'));
            exit;
        endif;

        foreach ($this->input->post('bls') as $b) :

            $bl = $this->managerBls->getBlById($b);
            $bl->hydrateLivraisons();
            /* récupération des informations client sur le premier Bl */
            if (!isset($client) || empty($client)) :
                $bl->hydrateClient();
                $client = $bl->getBlClient();
            endif;

            $bls[] = $bl;

            if (!empty($bl->getBlTvas())) :
                foreach ($bl->getBlTvas() as $t) :
                    $factureTotalTVA += $t->getTvaMontant();

                    if (isset($factureTvas[$t->getTvaTaux()])) :
                        $factureTvas[$t->getTvaTaux()] += $t->getTvaMontant();
                    else :
                        $factureTvas[$t->getTvaTaux()] = $t->getTvaMontant();
                    endif;
                endforeach;
            endif;

            foreach ($bl->getBlLivraisons() as $l) :
                $livraisons[] = $l;
            endforeach;

        endforeach;

        $this->db->trans_start();

        /* création d'une facture */
        $arrayFacture = array(
            'facturePdvId' => $this->session->userdata('loggedPdvId'),
            'factureDate' => time(),
            'factureClientId' => $client->getClientId(),
            'factureTotalTVA' => $factureTotalTVA,
            'factureTotalHT' => 0,
            'factureTotalTTC' => 0,
            'factureConditionsReglementId' => $client->getClientConditionReglementId(),
            'factureSolde' => 0,
            'factureEcheance' => $this->cxwork->calculEcheance(time(), $client->getClientConditionReglement()->getConditionReglementNom())
        );
        $newFacture = new Facture($arrayFacture);
        $this->managerFactures->ajouter($newFacture);

        /* On enregistre les TVA pour la facture */
        if ($client->getClientExonerationTVA() == 0) :
            foreach ($factureTvas as $taux => $montant) :
                $dataTVA = array('tvaFactureId' => $newFacture->getFactureId(), 'tvaTaux' => $taux, 'tvaMontant' => $montant);
                $tvaFacture = new Facturetva($dataTVA);
                $this->managerFacturetva->ajouter($tvaFacture);
            endforeach;
        endif;

        /* on ajoute les lignes de facturation */
        $factureTotalHT = 0;

        foreach ($livraisons as $l) :
            $livraisonHT = round($l->getLivraisonQte() * $l->getLivraisonArticle()->getArticlePrixNet(), 2);
            if ($this->session->userdata('venteExonerationTVA') == 0) :
                $livraisonTVA = round($livraisonHT * ($l->getLivraisonArticle()->getArticleTauxTVA() / 100), 2);
            else :
                $livraisonTVA = 0;
            endif;

            $factureTotalHT += $livraisonHT;

            $arrayLigne = array(
                'ligneFactureId' => $newFacture->getFactureId(),
                'ligneBlId' => $l->getLivraisonBlId(),
                'ligneLivraisonId' => $l->getLivraisonId(),
                'ligneProduitId' => $l->getLivraisonArticle()->getArticleProduitId(),
                'ligneDesignation' => $l->getLivraisonArticle()->getArticleDesignation(),
                'ligneUniteId' => $l->getLivraisonArticle()->getArticleUniteId(),
                'lignePrixUnitaire' => $l->getLivraisonArticle()->getArticlePrixUnitaire(),
                'ligneQte' => $l->getLivraisonQte(),
                'ligneRemise' => $l->getLivraisonArticle()->getArticleRemise(),
                'lignePrixNet' => $l->getLivraisonArticle()->getArticlePrixNet(),
                'ligneTotalHT' => $livraisonHT,
                'ligneTauxTVA' => $l->getLivraisonArticle()->getArticleTauxTVA()
            );
            $newLigne = new Factureligne($arrayLigne);
            $this->managerFacturelignes->ajouter($newLigne);
            unset($newLigne);
        endforeach;

        /* mise à jour du total de la facture */
        $newFacture->setFactureTotalHT($factureTotalHT);
        $newFacture->setFactureTotalTTC($factureTotalHT + $factureTotalTVA);

        $this->managerFactures->editer($newFacture);

        /* on recharge la facture pour mettre à jour les attributs */
        $facture = $this->managerFactures->getFactureById($newFacture->getFactureId());
        unset($newFacture);

        $facture->solde();
        $this->managerFactures->editer($facture);

        /* on indique dans les BL l'id de la facture */
        foreach ($bls as $b) :
            $b->setBlFactureId($facture->getFactureId());
            $this->managerBls->editer($b);
            unset($b);
        endforeach;

        $this->db->trans_complete();

        echo json_encode(array('type' => 'success', 'factureId' => $facture->getFactureId()));
        exit;
    }

    private function ajouterUnReglement($type, $mode, $factureId, $montant, $bdcId, $clientId, $sourceId = null) {
        //log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . $clientId);
        $this->db->trans_start();
        $secure = new Token;

        /* On passe le réglement source en inutile */
        if ($sourceId):
            $oldReglement = $this->managerReglements->getReglementById($sourceId);
            $oldReglement->setReglementUtile(0);
            $this->managerReglements->editer($oldReglement);
        endif;

        $dataReglement = array(
            'reglementDate' => time(),
            'reglementType' => $type,
            'reglementBdcId' => $bdcId,
            'reglementFactureId' => $factureId,
            'reglementClientId' => $clientId,
            'reglementModeId' => $mode,
            'reglementRemiseId' => null,
            'reglementMontant' => $montant,
            'reglementSourceId' => $sourceId ?: null,
            'reglementGroupeId' => $sourceId ? $oldReglement->getReglementGroupeId() : null,
            'reglementUtile' => 1,
            'reglementToken' => ''
        );

        $reglement = new Reglement($dataReglement);
        $this->managerReglements->ajouter($reglement);

        if (!$sourceId):
            $reglement->setReglementGroupeId($reglement->getReglementId());
        endif;

        /* Génération du Token */
        $chaine = $reglement->getReglementId() . number_format($reglement->getReglementMontant(), 2, '.', '') . $reglement->getReglementDate() . $reglement->getReglementSourceId() . $reglement->getReglementModeId();
        $token = $secure->getToken($chaine);
        $reglement->setReglementToken($token);

        $this->managerReglements->editer($reglement);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE):
            return false;
        else:
            return $reglement;
        endif;
    }

    public function addReglement() {

        if (!$this->form_validation->run('addReglement')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        if ($this->input->post('addReglementObjet') == 0) :
            $type = 1;
            $factureId = null;
        else :
            $type = 2;
            $factureId = $this->input->post('addReglementObjet');
        endif;
        //log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . 'Source client Id Session : ' . $this->session->userdata('venteClientId'));
        $reglement = $this->ajouterUnReglement($type, $this->input->post('addReglementMode'), $factureId, $this->input->post('addReglementMontant'), $this->session->userdata('venteId'), $this->session->userdata('venteClientId'), $this->input->post('addReglementId'));
        if ($reglement):
            if ($factureId):
                $facture = $this->managerFactures->getFactureById($factureId);
                $facture->solde();
                $this->managerFactures->editer($facture);
            endif;
            echo json_encode(array('type' => 'success'));
        else:
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        endif;
    }

    /**
     * Affecte un reglement à une facture
     */
    public function affecteReglementAFacture() {

        if (!$this->form_validation->run('affecteReglement')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
        $reglement = $this->managerReglements->getReglementById($this->input->post('reglementId'));
        if ($reglement->getReglementFactureId()):
            $oldFacture = $this->managerFactures->getFactureById($reglement->getReglementFactureId());
        endif;

        $newFacture = $this->managerFactures->getFactureById($this->input->post('factureId'));

        $reglement->setReglementFactureId($newFacture ? $newFacture->getFactureId() : null);
        $this->managerReglements->editer($reglement);
        if ($newFacture):
            $newFacture->solde();
            $this->managerFactures->editer($newFacture);
        endif;

        if (isset($oldFacture)):
            $oldFacture->solde();
            $this->managerFactures->editer($oldFacture);
        endif;

        echo json_encode(array('type' => 'success'));
        exit;
    }

    public function recalculeSolde($factureId) {
        if (!$this->existFacture($factureId)):
            redirect('ventes/bdcListe');
            exit;
        endif;
        $facture = $this->managerFactures->getFactureById($factureId);
        $facture->solde();
        $this->managerFactures->editer($facture);
        redirect('factures/ficheFacture/' . $facture->getFactureId());
        exit;
    }

    public function forceSolde($factureId, $solde) {
        if (!$this->existFacture($factureId)):
            redirect('ventes/bdcListe');
            exit;
        endif;
        $facture = $this->managerFactures->getFactureById($factureId);
        $facture->setFactureSolde($solde);
        $this->managerFactures->editer($facture);
        redirect('factures/ficheFacture/' . $facture->getFactureId());
        exit;
    }

    /**
     * Retourne le reste à payer d'une facture
     */
    public function resteAPayer() {
        if (!$this->form_validation->run('getFacture')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
        $facture = $this->managerFactures->getFactureById($this->input->post('factureId'));
        $facture->solde();
        echo json_encode(array('type' => 'success', 'RAP' => $facture->getFactureSolde()));
        exit;
    }

    public function ficheFacture($factureId) {

        if (!$this->existFacture($factureId)):
            redirect('ventes/listeBdc');
            exit;
        endif;

        $this->venteInit();
        $facture = $this->managerFactures->getFactureById($factureId);
        $facture->hydrateClient();
        $facture->hydrateReglements();
        if ($facture->getFactureReglements()):
            foreach ($facture->getFactureReglements() as $r):
                $r->hydrateHistorique();
            endforeach;
        endif;
        $facture->hydrateAvoirs();

        $this->session->set_userdata(array('venteFactureId' => $facture->getFactureId(), 'venteClientId' => $facture->getFactureClientId(), 'venteId' => null));

        $data = array(
            'facture' => $facture,
            'title' => 'Détail facture ' . $facture->getFactureId(),
            'description' => '',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function genererAvoir($factureId = null) {
        if (!$this->existFacture($factureId) || !$factureId):
            redirect('ventes/bdcListe');
            exit;
        endif;
        $facture = $this->managerFactures->getFactureById($factureId);
        $facture->hydrateClient();
        $this->venteInit();
        $this->cart->destroy();

        /* on change la session */
        $session = array(
            'venteType' => 3,
            'venteFactureId' => $facture->getFactureId(),
            'venteClientId' => $facture->getFactureClientId(),
            'venteClientType' => $facture->getFactureClient()->getClientType(),
            'venteExonerationTVA' => $facture->getFactureClient()->getClientExonerationTVA()
        );
        $this->session->set_userdata($session);

        $i = 0;
        foreach ($facture->getFactureLignes() as $l) :

            if ($l->getLigneProduitId() == 0) :
                $i++;
                $id = 'Unique' . $i;
            else :
                $id = $l->getLigneProduitId();
            endif;

            $this->ajouteArticleAuPanier(null, $id, 0, $l->getLignePrixNet(), $l->getLigneDesignation(), $l->getLigneRemise(), array(
                'produitId' => $l->getLigneProduitId(),
                'multiple' => $l->getLigneProduitId() ? $this->managerProduits->getProduitById($l->getLigneProduitId())->getProduitMultiple() : 1,
                'uniteId' => $l->getLigneUniteId(),
                'tauxTVA' => $l->getLigneTauxTVA(),
                'prixUnitaire' => $l->getLignePrixUnitaire(),
                'qteVendue' => $l->getLigneQte()
                    )
            );

        endforeach;

        $this->ajouteArticleAuPanier(null, 'Libre', 0, 0, '', 0, array(
            'produitId' => null,
            'uniteId' => 1,
            'multiple' => 1,
            'tauxTVA' => number_format(self::tauxTVA, 2, '.', ''),
            'prixUnitaire' => 0,
            'qteVendue' => 100000
                )
        );
        $this->getTotauxTaxes();

        redirect('factures/avoir');
        exit;
    }

    public function avoir() {

        if (!$this->session->userdata('venteFactureId')):
            redirect('ventes/bdcListe');
            exit;
        endif;

        $facture = $this->managerFactures->getFactureById($this->session->userdata('venteFactureId'));
        $facture->hydrateClient();
        $facture->hydrateAvoirs();

        $data = array(
            'facture' => $facture,
            'title' => 'Générer un avoir',
            'description' => '',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function modAvoirQte() {

        if (!$this->form_validation->run('modAvoirQte')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            $this->cart->update(array('rowid' => $this->input->post('rowId'), 'qty' => $this->input->post('qte')));
            $this->getTotauxTaxes();
            echo json_encode(array('type' => 'success'));
        endif;
        exit;
    }

    public function modAvoirPrix() {

        if (!$this->form_validation->run('modAvoirPrix')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            $this->cart->update(array('rowid' => $this->input->post('rowId'), 'price' => $this->input->post('prix')));
            $this->getTotauxTaxes();
            echo json_encode(array('type' => 'success'));
        endif;
        exit;
    }

    public function modAvoirName() {

        if (!$this->form_validation->run('modAvoirName')):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        else:
            $this->cart->update(array('rowid' => $this->input->post('rowId'), 'name' => $this->input->post('name')));
            echo json_encode(array('type' => 'success'));
        endif;
        exit;
    }

    public function addAvoir() {

        if (!$this->existFacture($this->session->userdata('venteFactureId')) || $this->cart->total() == 0):
            echo json_encode(array('type' => 'error', 'message' => 'Toutes les quantité sont nulles'));
            exit;
        endif;

        $facture = $this->managerFactures->getFactureById($this->session->userdata('venteFactureId'));
        $facture->hydrateAvoirs();
        $facture->hydrateClient();
        $client = $facture->getFactureClient();

        if ($this->cart->total() > ($facture->getFactureTotalHT() - $facture->getFactureTotalAvoirs())):
            echo json_encode(array('type' => 'error', 'message' => 'Le total de l\'avoir est supérieur à la valeur de la facture.'));
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

        /* création d'un avoir */
        $arrayAvoir = array(
            'avoirPdvId' => $this->session->userdata('loggedPdvId'),
            'avoirFactureId' => $facture->getFactureId(),
            'avoirDate' => time(),
            'avoirClientId' => $client->getClientId(),
            'avoirTotalTVA' => $tva,
            'factureTotalHT' => 0,
            'factureTotalTTC' => 0
        );
        $avoir = new Avoir($arrayAvoir);
        $this->managerAvoirs->ajouter($avoir);

        /* On enregistre les TVA pour l'avoir */
        if ($this->session->userdata('venteExonerationTVA') == 0) :
            foreach ($this->session->userdata('venteTVA') as $taux => $montant) :
                $dataTVA = array('tvaAvoirId' => $avoir->getAvoirId(), 'tvaTaux' => $taux, 'tvaMontant' => $montant);
                $tvaAvoir = new Avoirtva($dataTVA);
                $this->managerAvoirtva->ajouter($tvaAvoir);
            endforeach;
        endif;

        /* on ajoute les lignes de l'avoir */
        $avoirTotalHT = 0;

        foreach ($this->cart->contents() as $item) :
            if ($item['qty'] > 0 && $item['price'] > 0):
                $ligne = $this->saveNewLigneAvoir($item, $avoir->getAvoirId());
                $avoirTotalHT += $ligne->getLigneTotalHT();
            endif;
        endforeach;

        /* mise à jour du total de la facture */
        $avoir->setAvoirTotalHT($avoirTotalHT);
        $avoir->setAvoirTotalTTC($avoirTotalHT + $tva);

        $this->managerAvoirs->editer($avoir);

        $facture->hydrateAvoirs();
        $facture->solde();
        $this->managerFactures->editer($facture);

        $this->db->trans_complete();

        echo json_encode(array('type' => 'success', 'factureId' => $facture->getFactureId()));
        exit;
    }

    private function saveNewLigneAvoir($item, $avoirId) {

        $ligneTotalHT = round($item['price'] * $item['qty'], 2);

        $dataLigne = array(
            'ligneAvoirId' => $avoirId,
            'ligneProduitId' => $item['options']['produitId'],
            'ligneUniteId' => $item['options']['uniteId'],
            'ligneDesignation' => $item['name'],
            'ligneQte' => $item['qty'],
            'lignePrixUnitaire' => $item['options']['prixUnitaire'],
            'ligneRemise' => $item['remise'],
            'lignePrixNet' => $item['price'],
            'ligneTotalHT' => $ligneTotalHT,
            'ligneTauxTVA' => $item['options']['tauxTVA']
        );

        $newLigne = new Avoirligne($dataLigne);
        $this->managerAvoirlignes->ajouter($newLigne);
        return $newLigne;
    }

    public function sendFactureByEmail() {

        if (!$this->existFacture($this->input->post('factureId'))):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        $pdv = $this->managerPdv->getPdvById($this->session->userdata('loggedPdvId'));
        $facture = $this->managerFactures->getFactureById($this->input->post('factureId'));
        $facture->hydrateClient();
        if (!$facture->getFactureClient()->getClientEmail() || !valid_email($facture->getFactureClient()->getClientEmail())):
            echo json_encode(array('type' => 'error', 'message' => 'Le client n\'a pas d\'email renseigné ou cet email est invalide.'));
            exit;
        endif;

        if ($this->own->emailFacture($facture, $pdv)):
            echo json_encode(array('type' => 'success'));
        else:
            echo json_encode(array('type' => 'error', 'message' => 'Erreur lors de l\'envoi de l\'email'));
        endif;
        exit;
    }

    public function sendAvoirByEmail() {

        if (!$this->existAvoir($this->input->post('avoirId'))):
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;

        $pdv = $this->managerPdv->getPdvById($this->session->userdata('loggedPdvId'));
        $avoir = $this->managerAvoirs->getAvoirById($this->input->post('avoirId'));
        $avoir->hydrateClient();
        if (!$avoir->getAvoirClient()->getClientEmail() || !valid_email($avoir->getAvoirClient()->getClientEmail())):
            echo json_encode(array('type' => 'error', 'message' => 'Le client n\'a pas d\'email renseigné ou cet email est invalide.'));
            exit;
        endif;

        if ($this->own->emailAvoir($avoir, $pdv)):
            echo json_encode(array('type' => 'success'));
        else:
            echo json_encode(array('type' => 'error', 'message' => 'Erreur lors de l\'envoi de l\'email'));
        endif;
        exit;
    }

}
