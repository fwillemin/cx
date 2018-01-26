<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Documents extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in()) :
            redirect('secure/login');
        endif;

        // Include the main TCPDF library (search for installation path).
        require_once('application/libraries/tcpdf/tcpdf.php');

        $this->pdv = $this->managerPdv->getPdvById($this->session->userdata('loggedPdvId'));
        $this->piedPage1 = $this->pdv->getPdvRaisonSociale() . ', ' . $this->pdv->getPdvAdresse1() . ', ' . $this->pdv->getPdvCp() . ' ' . $this->pdv->getPdvVille();
        $this->piedPage2 = '<br>Siret: ' . $this->pdv->getPdvSiren() . ' - APE: ' . $this->pdv->getPdvApe() . ' - N° TVA Intracommunautaire : ' . $this->pdv->getPdvTvaIntracom();
    }

    public function editionDevis($devisId = null) {

        if ($devisId) :

            $devis = $this->managerDevis->getDevisById($devisId);
            if (empty($devis)) :
                redirect('chiffrages/');
                exit;
            endif;
            $articles = $this->managerDevisarticles->getArticlesByDevisId($devis->getDevisId());

            $data = array(
                'pdv' => $this->pdv,
                'devis' => $devis,
                'articles' => $articles,
                'title' => 'CX - Devis',
                'description' => '',
                'keywords' => '',
                'content' => $this->viewFolder . __FUNCTION__
            );
            $this->load->view('template/contentDocuments', $data);

            // Extend the TCPDF class to create custom Header and Footer
            $html = $this->output->get_output();

            // create new PDF document
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, false, $this->piedPage1, $this->piedPage2);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor($this->pdv->getPdvNomCommercial());
            $pdf->SetTitle('Devis ' . $devis->getDevisId());
            $pdf->SetSubject('Devis ' . $devis->getDevisId());

            $pdf->SetMargins(5, 5, 5);
            // set auto page breaks
            $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
            $pdf->AddPage();

            $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

            $pdf->Output('Devis ' . $devis->getDevisId() . '.pdf', 'FI');

        else :
            redirect('chiffrages/');
            exit;
        endif;
    }

    public function editionBdc($bdcId = null) {

        if (!$bdcId || !$this->existBdc($bdcId)) :
            redirect('chiffrages/');
            exit;
        endif;

        $bdc = $this->managerBdc->getBdcById($bdcId);
        $bdc->hydrateArticles();

        $data = array(
            'pdv' => $this->pdv,
            'bdc' => $bdc,
            //'articles' => $articles,
            'title' => 'Bon de commande',
            'description' => '',
            'keywords' => '',
            'content' => $this->viewFolder . __FUNCTION__
        );
        $this->load->view('template/contentDocuments', $data);

        // Extend the TCPDF class to create custom Header and Footer
        $html = $this->output->get_output();

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, false, $this->piedPage1, $this->piedPage2);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($this->pdv->getPdvNomCommercial());
        $pdf->SetTitle('Commande ' . $bdc->getBdcId());
        $pdf->SetSubject('Commande ' . $bdc->getBdcId());

        $pdf->SetMargins(5, 5, 5);
        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->AddPage();

        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        $pdf->Output('Commande ' . $bdc->getBdcId() . '.pdf', 'FI');
    }

    /**
     * Exporte le Bon de livraison en pdf
     * @param int $blId ID du Bon de livraison
     * @param bool $nonchiffre TRUE pour editer le bon de livraison en chiffré
     */
    public function editionBl($blId = null, $nonchiffre = null) {

        if (!$blId || !$this->existBl($blId)) :
            redirect('ventes/');
            exit;
        endif;

        $bl = $this->managerBls->getBlById(intval($blId));
        $bl->hydrateLivraisons();

        if (!$nonchiffre) :
            $option = 'chiffre';
        else :
            $option = 'nonchiffre';
        endif;
        $bdc = $this->managerBdc->getBdcById($bl->getBlBdcId());
        $data = array(
            'pdv' => $this->pdv,
            'bl' => $bl,
            'chiffrage' => $nonchiffre ? false : true,
            'client' => $bdc->getBdcClient(),
            'title' => 'Bon de livraison',
            'description' => '',
            'keywords' => '',
            'content' => $this->viewFolder . __FUNCTION__
        );
        $this->load->view('template/contentDocuments', $data);

        // Extend the TCPDF class to create custom Header and Footer
        $html = $this->output->get_output();

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, false, $this->piedPage1, $this->piedPage2);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($this->pdv->getPdvNomCommercial());
        $pdf->SetTitle('Bl ' . $bl->getBlId());
        $pdf->SetSubject('Bl ' . $bl->getBlId());

        $pdf->SetMargins(5, 5, 5);
        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->AddPage();

        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        $pdf->Output('Bl ' . $bl->getBlId() . '.pdf', 'FI');
    }

    public function editionFacture($factureId = null) {

        if (!$factureId || !$this->existFacture($factureId)) :
            redirect('ventes/listeBdc');
            exit;
        endif;
        $facture = $this->managerFactures->getFactureById($factureId);
        $facture->hydrateClient();
        $facture->hydrateReglements();

        $data = array(
            'pdv' => $this->pdv,
            'facture' => $facture,
            'title' => 'CX - Facture',
            'description' => '',
            'keywords' => '',
            'content' => $this->viewFolder . __FUNCTION__
        );
        $this->load->view('template/contentDocuments', $data);

        // Extend the TCPDF class to create custom Header and Footer
        $html = $this->output->get_output();

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, false, $this->piedPage1, $this->piedPage2);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($this->pdv->getPdvNomCommercial());
        $pdf->SetTitle('Facture ' . $facture->getFactureId());
        $pdf->SetSubject('Facture ' . $facture->getFactureId());

        $pdf->SetMargins(5, 5, 5);
        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->AddPage();

        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        $pdf->Output('Facture ' . $facture->getFactureId() . '.pdf', 'FI');
    }

    public function editionAvoir($avoirId = null) {

        if (!$avoirId || !$this->existAvoir($avoirId)) :
            redirect('ventes/bdcListe');
            exit;
        endif;
        $avoir = $this->managerAvoirs->getAvoirById($avoirId);
        $avoir->hydrateClient();

        $data = array(
            'pdv' => $this->pdv,
            'avoir' => $avoir,
            'title' => 'Avoir ' . $avoir->getAvoirId(),
            'description' => '',
            'keywords' => '',
            'content' => $this->viewFolder . __FUNCTION__
        );
        $this->load->view('template/contentDocuments', $data);

        // Extend the TCPDF class to create custom Header and Footer
        $html = $this->output->get_output();

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, false, $this->piedPage1, $this->piedPage2);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($this->pdv->getPdvNomCommercial());
        $pdf->SetTitle('Avoir ' . $avoir->getAvoirId());
        $pdf->SetSubject('Avoir ' . $avoir->getAvoirId());

        $pdf->SetMargins(5, 5, 5);
        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->AddPage();

        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        $pdf->Output('Avoir ' . $avoir->getAvoirId() . '.pdf', 'FI');
    }

    public function journalDesEncaissements($start, $end) {

        if ($start && $end) :
            $reglements = $this->managerReglements->liste(array('reglementDate >=' => $start, 'reglementDate <=' => $end, 'reglementUtile' => 1), 'reglementDate ASC');
            if ($reglements):
                foreach ($reglements as $r) :
                    $r->hydrateClient();
                endforeach;
            endif;

            $data = array(
                'pdv' => $this->pdv,
                'debut' => $start,
                'fin' => $end,
                'reglements' => $reglements,
                'title' => 'CX - Journal des encaissements',
                'description' => '',
                'keywords' => '',
                'content' => $this->viewFolder . __FUNCTION__
            );
            $this->load->view('template/contentDocuments', $data);

            // Extend the TCPDF class to create custom Header and Footer
            $html = $this->output->get_output();

            // create new PDF document
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, false, $this->piedPage1, $this->piedPage2);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor($this->pdv->getPdvNomCommercial());
            $pdf->SetTitle('Journal des encaissements');
            $pdf->SetSubject('Journal des encaissements');

            $pdf->SetMargins(5, 5, 5);
            // set auto page breaks
            $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
            $pdf->AddPage();

            $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

            $pdf->Output('Journal des encaissements.pdf', 'FI');
        else :
            redirect('facturation/encaissements');
            exit;
        endif;
    }

    public function journalDesVentes($start, $end) {

        if ($start && $end) :
            $factures = $this->managerFactures->liste(array('factureDate >=' => $start, 'factureDate <=' => $end));
            if ($factures):
                foreach ($factures as $f) :
                    $f->hydrateClient();
                endforeach;
            endif;
            $avoirs = $this->managerAvoirs->liste(array('avoirDate >=' => $start, 'avoirDate <=' => $end));
            if ($avoirs):
                foreach ($avoirs as $a) :
                    $a->hydrateClient();
                endforeach;
            endif;

            $data = array(
                'pdv' => $this->pdv,
                'debut' => $start,
                'fin' => $end,
                'factures' => $factures,
                'avoirs' => $avoirs,
                'title' => 'CX - Journal des ventes',
                'description' => '',
                'keywords' => '',
                'content' => $this->viewFolder . __FUNCTION__
            );
            $this->load->view('template/contentDocuments', $data);

            // Extend the TCPDF class to create custom Header and Footer
            $html = $this->output->get_output();

            // create new PDF document
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, false, $this->piedPage1, $this->piedPage2);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor($this->pdv->getPdvNomCommercial());
            $pdf->SetTitle('Journal des ventes');
            $pdf->SetSubject('Journal des ventes');

            $pdf->SetMargins(5, 5, 5);
            // set auto page breaks
            $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
            $pdf->AddPage();

            $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

            $pdf->Output('Journal des ventes.pdf', 'FI');
        else :
            redirect('facturation/encaissements');
            exit;
        endif;
    }

}
