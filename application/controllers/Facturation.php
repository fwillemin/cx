<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Facturation extends My_Controller {

    public function __construct() {
        parent::__construct();

        $this->view_folder = strtolower(__CLASS__) . '/';
        /* Connexion */
        if (!$this->ion_auth->logged_in() || !$this->session->userdata('loggedPdvId')) :
            redirect('secure/login');
        endif;
    }

    /**
     * Liste des Bls non facturés
     */
    public function blNonFactures() {

        $bls = $this->managerBls->liste(array('blFactureId' => null), 'c.clientId, bdc.bdcId ASC');
        if (!empty($bls)) :
            foreach ($bls as $b) :
                $b->hydrateClient();
            endforeach;
        endif;

        $data = array(
            'bls' => $bls,
            'client' => $this->managerClients->liste(),
            'title' => 'CX - Facturation - Bl non facturés',
            'description' => 'Liste des BL non facturés',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    private function _getCaissePeriode($start, $end) {

        /* on recherche le dernier controle depuis la date start */
        $lastControle = $this->managerCaisses->dernierControle($start);

        if (!empty($lastControle)) :
            $startSelection = $lastControle->getCaisseDate();
            $saisies = $this->managerCaisses->liste(array('caisseDate >=' => $startSelection, 'caisseDate <=' => $end));
            $reglements = $this->managerReglements->liste(array('reglementDate >=' => $startSelection, 'reglementDate <=' => $end, 'reglementModeId' => 3, 'reglementDelete' => 0));
            $acomptes = $this->managerAcomptes->liste(array('acompteDate >=' => $startSelection, 'acompteDate <=' => $end, 'acompteModeReglementId' => 3));

            /* on genere un array qui fusionne le tout */
            $caisse = array();
            if (!empty($saisies)) :
                foreach ($saisies as $c) :
                    $caisse[] = array('origine' => 'caisse', 'id' => $c->getCaisseId(), 'date' => $c->getCaisseDate(), 'type' => $c->getCaisseType(), 'objet' => $c->getCaisseDetail(), 'montant' => $c->getCaisseMontant());
                endforeach;
            endif;
            if (!empty($reglements)) :
                foreach ($reglements as $r) :
                    $caisse[] = array('origine' => 'reglement', 'id' => $r->getReglementId(), 'date' => $r->getReglementDate(), 'type' => 'Facture', 'objet' => $r->getReglementFactureId(), 'montant' => $r->getReglementTotal());
                endforeach;
            endif;
            if (!empty($acomptes)) :
                foreach ($acomptes as $a) :
                    $caisse[] = array('origine' => 'acompte', 'id' => $a->getAcompteId(), 'date' => $a->getAcompteDate(), 'type' => 'Acompte', 'objet' => $a->getAcompteBdcId(), 'montant' => $a->getAcompteTotal());
                endforeach;
            endif;

            $caisseTriDate = $this->cxwork->array_msort($caisse, array('date' => SORT_ASC));
        else :
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' : ' . 'Pas de fond de caisse');
            $caisseTriDate = array();
        endif;

        return $caisseTriDate;
    }

    public function caisse() {

        $periode = $this->_getLimitesPeriode();

        $listeCaisse = $this->_getCaissePeriode($periode['start'], $periode['end']);

        $data = array(
            'caisse' => $listeCaisse,
            'debut' => $periode['start'],
            'fin' => $periode['end'],
            'title' => 'CX - Gestion de la caisse',
            'description' => 'Gestion de la caisse',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function feuilleDeCaisse($start, $end) {

        if ($start && $end) :
            $listeCaisse = $this->_getCaissePeriode($start, $end);

            $data = array(
                'debut' => $start,
                'fin' => $end,
                'caisse' => $listeCaisse,
                'title' => 'CX - Feuille de caisse',
                'description' => '',
                'keywords' => '',
                'content' => $this->view_folder . __FUNCTION__
            );
            $this->load->view('template/content_only', $data);

            // Include the main TCPDF library (search for installation path).
            require_once('application/libraries/tcpdf/tcpdf.php');
            // Extend the TCPDF class to create custom Header and Footer
            $html = $this->output->get_output();

            // create new PDF document
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('CX');
            $pdf->SetTitle('Feuille de caisse');
            $pdf->SetSubject('Feuille de caisse');
            $pdf->SetKeywords('CX, Feuille de caisse');

            $pdf->SetMargins(5, 5, 5);
            // set auto page breaks
            $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
            $pdf->AddPage();

            $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
            $pdf->Output('feuilleDeCaisse.pdf', 'FI');
        else :
            redirect('facturation/caisse');
            exit;
        endif;
    }

    public function remisesDeCheques($start, $end) {

        if ($start && $end) :
            $remises = $this->managerRemises->liste(array('remiseDate >=' => $start, 'remiseDate <=' => $end));
            if (!empty($remises)) :
                foreach ($remises as $r) :
                    foreach ($r->getRemiseReglements() as $reg) :
                        $reg->hydrateClient();
                    endforeach;
                endforeach;
            endif;

            $data = array(
                'debut' => $start,
                'fin' => $end,
                'remises' => $remises,
                'title' => 'CX - Feuille de caisse',
                'description' => '',
                'keywords' => '',
                'content' => $this->view_folder . __FUNCTION__
            );
            $this->load->view('template/content_only', $data);

            // Include the main TCPDF library (search for installation path).
            require_once('application/libraries/tcpdf/tcpdf.php');
            // Extend the TCPDF class to create custom Header and Footer
            $html = $this->output->get_output();

            // create new PDF document
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('CX');
            $pdf->SetTitle('Remises de chèque');
            $pdf->SetSubject('Remises de chèque');
            $pdf->SetKeywords('CX, Remises de chèque');

            $pdf->SetMargins(5, 5, 5);
            // set auto page breaks
            $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
            $pdf->AddPage();

            $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
            $pdf->Output('Remises de chèque.pdf', 'FI');
        else :
            redirect('facturation/cheques');
            exit;
        endif;
    }

    /* Affiche les factures non Payées mais aussi la facture lors d'une recherche dans le menu de Facturation */

    public function facturesNonPayees($factureId = null) {

        if (!$factureId || $factureId == 'NaN') :
            $factures = $this->managerFactures->liste(array('FactureSolde <>' => 0, 'factureDelete' => 0));
        else :
            $factures = $this->managerFactures->liste(array('factureId' => intval($factureId)));
        endif;

        $data = array(
            'factures' => $factures,
            //'client' => $this->m_client->liste(),
            'title' => 'CX - Facturation - Bl non facturés',
            'description' => 'Liste des BL non facturés',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    private function _getLimitesPeriode() {
        if ($this->session->userdata('extractStart')) :
            $start = $this->session->userdata('extractStart');
            $end = $this->session->userdata('extractEnd');
        else :
            $start = mktime(0, 0, 0, date('m'), 1, date('Y'));
            $end = mktime(23, 59, 59, date('m'), date('t'), date('Y'));
        endif;

        return array('start' => $start, 'end' => $end);
    }

    public function encaissements() {

        $periode = $this->_getLimitesPeriode();

        $factures = $this->managerFactures->liste(array('factureDate >=' => $periode['start'], 'factureDate <=' => $periode['end']));
        if ($factures):
            foreach ($factures as $f) :
                $f->hydrateClient();
            endforeach;
        endif;
        $avoirs = $this->managerAvoirs->liste(array('avoirDate >=' => $periode['start'], 'avoirDate <=' => $periode['end']));
        if ($avoirs):
            foreach ($avoirs as $a) :
                $a->hydrateClient();
            endforeach;
        endif;

        $reglements = $this->managerReglements->liste(array('reglementDate >=' => $periode['start'], 'reglementDate <=' => $periode['end'], 'reglementUtile' => 1));
        if (!empty($reglements)) :
            foreach ($reglements as $r) :
                $r->hydrateClient();
                $r->hydrateHistorique();
            endforeach;
        endif;

        $data = array(
            'factures' => $factures,
            'avoirs' => $avoirs,
            'reglements' => $reglements,
            'debut' => $periode['start'],
            'fin' => $periode['end'],
            'title' => 'Gestion des encaissements',
            'description' => 'Extraction date à date',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function addPeriode() {
        $this->form_validation->set_rules('start', 'Start', 'required|trim');
        $this->form_validation->set_rules('end', 'End', 'required|trim');
        if ($this->form_validation->run()) :
            $this->session->set_userdata(array('extractStart' => $this->cxwork->mktimeFromInputDate($this->input->post('start')), 'extractEnd' => ($this->cxwork->mktimeFromInputDate($this->input->post('end')) + 86399)));
            echo json_encode(array('type' => 'success'));
            exit;
        else :
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' : ' . validation_errors());
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
    }

    public function addMouvementCaisse() {

        $this->form_validation->set_rules('addCaisseId', 'Id', 'is_natural_no_zero|trim');
        $this->form_validation->set_rules('addCaisseDate', 'Date', 'required|trim');
        $this->form_validation->set_rules('addCaisseType', 'Type', 'required|in_list[1,2]|trim');
        $this->form_validation->set_rules('addCaisseMontant', 'Montant', 'required|numeric|trim');
        $this->form_validation->set_rules('addCaisseDetail', 'Détail', 'trim');

        if ($this->form_validation->run()) :
            if ($this->input->post('addCaisseId')) :
                $caisse = $this->managerCaisses->getCaisseById(intval($this->input->post('addCaisseId')));
                if (empty($caisse)) :
                    echo json_encode(array('type' => 'error', 'message' => 'Mouvement de caisse intraçable...'));
                    exit;
                endif;
                $caisse->setCaisseDate($this->cxwork->mktimeFromInputDate($this->input->post('addCaisseDate')));
                $caisse->setCaisseType(intval($this->input->post('addCaisseType')));
                $caisse->setCaisseMontant(floatval($this->input->post('addCaisseMontant')));
                $caisse->setCaisseDetail($this->input->post('addCaisseDetail'));

                $this->managerCaisses->editer($caisse);
            else :
                $data = array(
                    'caisseDate' => $this->cxwork->mktimeFromInputDate($this->input->post('addCaisseDate')),
                    'caissePdvId' => $this->session->userdata('loggedPdvId'),
                    'caisseType' => intval($this->input->post('addCaisseType')),
                    'caisseMontant' => floatval($this->input->post('addCaisseMontant')),
                    'caisseDetail' => $this->input->post('addCaisseDetail')
                );
                $caisse = new Caisse($data);
                $this->managerCaisses->ajouter($caisse);
            endif;
            echo json_encode(array('type' => 'success'));
            exit;
        else :
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' : ' . 'Impossible d\'ajouter un mouvement de caisse : ' . validation_errors());
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
    }

    public function getMouvementCaisse() {
        $this->form_validation->set_rules('caisseId', 'Caisse Id', 'required|is_natural_no_zero|trim');

        if ($this->form_validation->run()) :
            $caisse = $this->managerCaisses->getCaisseById(intval($this->input->post('caisseId')), 'array');
            if (empty($caisse)) :
                echo json_encode(array('type' => 'error', 'message' => 'Mouvement de caisse intraçable...'));
                exit;
            endif;
            echo json_encode(array('type' => 'success', 'caisse' => $caisse));
            exit;
        else :
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' : ' . 'Erreur recupération d\'un mouvement de caisse');
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
    }

    public function delMouvementCaisse() {
        $this->form_validation->set_rules('caisseId', 'ID du mouvement de caisse', 'is_natural_no_zero|required|trim');
        if ($this->form_validation->run()) :
            $caisse = $this->managerCaisses->getCaisseById(intval($this->input->post('caisseId')));
            if (empty($caisse)) :
                echo json_encode(array('type' => 'error', 'message' => 'Mouvement de caisse intraçable...'));
                exit;
            endif;
            $this->managerCaisses->delete($caisse);
            echo json_encode(array('type' => 'success'));
            exit;
        else :
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' : ' . 'Impossible de supprimer le mouvement de caisse : ' . validation_errors());
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
    }

    public function cheques() {

        $periode = $this->_getLimitesPeriode();

        $cheques = $this->managerReglements->getChequesPeriode($periode['start'], $periode['end']);
        if (!empty($cheques)) :
            foreach ($cheques as $c) :
                $c->hydrateClient();
                $c->hydrateRemise();
            endforeach;
        endif;

        $data = array(
            'cheques' => $cheques,
            'debut' => $periode['start'],
            'fin' => $periode['end'],
            'title' => 'CX - Gestion des remises de chèque',
            'description' => 'Extraction date à date',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function addRemiseCheque() {

        $this->form_validation->set_rules('date', 'Date', 'required|trim');
        $this->form_validation->set_rules('banque', 'Banque de depôt', 'trim');
        $this->form_validation->set_rules('total', 'total', 'numeric|required|trim');

        if ($this->form_validation->run()) :
            if (empty($this->input->post('reglements'))) :
                echo json_encode(array('type' => 'error', 'message' => 'Aucun chèque dans cette remise'));
                exit;
            else :
                /* on ajoute une remise de chèques */
                $dataRemise = array(
                    'remiseDate' => $this->cxwork->mktimeFromInputDate($this->input->post('date')),
                    'remiseBanque' => $this->input->post('banque'),
                    'remisePdvId' => $this->session->userdata('loggedPdvId'),
                    'remiseTotal' => floatval($this->input->post('total'))
                );
                $remise = new Remise($dataRemise);
                $this->managerRemises->ajouter($remise);

                /* on met à jour les reglements avec l'ID de la remise */
                foreach ($this->input->post('reglements') as $r) :
                    $reglement = $this->managerReglements->getReglementById($r);
                    $reglement->setReglementRemiseId($remise->getRemiseId());
                    $this->managerReglements->editer($reglement);
                    unset($reglement);
                endforeach;

                echo json_encode(array('type' => 'success'));
                exit;
            endif;
        else :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
    }

    public function getRemise() {

        $this->form_validation->set_rules('remiseId', 'Remise ID', 'required|is_natural_no_zero|trim');
        if ($this->form_validation->run()) :
            $remise = $this->managerRemises->getRemiseById(intval($this->input->post('remiseId')), 'array');
            $reglements = $this->managerReglements->getReglementsByRemiseId($remise['remiseId'], 'array');

            $reglementsRetour = array();
            if (!empty($reglements)) :
                foreach ($reglements as $r) :
                    $client = $this->managerClients->getClientById($r['reglementClientId']);
                    if ($client->getClientType() == 1) :
                        $r['reglementClient'] = $client->getClientNom() . ' ' . $client->getClientPrenom();
                    else :
                        $r['reglementClient'] = $client->getClientRaisonSociale();
                    endif;
                    $reglementsRetour[] = $r;
                endforeach;
            endif;

            echo json_encode(array('type' => 'success', 'remise' => $remise, 'reglements' => $reglementsRetour));
            exit;
        else :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
    }

    public function delRemise() {
        $this->form_validation->set_rules('remiseId', 'Remise ID', 'required|is_natural_no_zero|trim');
        if ($this->form_validation->run()) :
            $remise = $this->managerRemises->getRemiseById(intval($this->input->post('remiseId')));
            if (empty($remise)) :
                echo json_encode(array('type' => 'error', 'message' => 'Remise introuvable'));
                exit;
            endif;

            foreach ($remise->getRemiseReglements() as $r) :
                $r->setReglementRemiseId(0);
                $this->managerReglements->editer($r);
            endforeach;
            $this->managerRemises->delete($remise);

            echo json_encode(array('type' => 'success'));
            exit;
        else :
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' : ' . validation_errors());
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
    }

    function soldeFacture($factureId) {
        /* retourne le reste à regler d'une facture. Idem resteAPayer() mais en local du controlleur */
        $total = round($this->m_facture->getOne($factureId)->factureTotal * (1 + $this->m_facture->getOne($factureId)->factureTva / 100), 2);
        $reglements = $this->m_reglement->reglementsFacture($factureId);
        if (!empty($reglements)) :
            foreach ($reglements as $r) {
                $total -= $r->reglementTotal;
            }
        endif;
        $acomptes = $this->m_acompte->liste(array('acompteFactureId' => intval($factureId)));
        if (!empty($acomptes)) :
            foreach ($acomptes as $a) {
                $total -= $a->acompteTotal;
            }
        endif;
        return $total;
    }

    /**
     * GRAPHS
     */
    public function getEncaissementsChart() {

        $nbJours = date('t', mktime(0, 0, 0, $this->session->userdata('analyseMois'), 1, $this->session->userdata('analyseAnnee')));

        $labels = $donnees = array();
        for ($i = 1; $i <= $nbJours; $i++) {
            $labels[] = $i;
        }

        for ($i = 1; $i <= $nbJours; $i++) {
            $donnees[] = $this->getCAJournee($i, $this->session->userdata('analyseMois'), $this->session->userdata('analyseAnnee'));
        }
        echo json_encode(array('labels' => $labels, 'data' => $donnees));
        exit;
    }

}
