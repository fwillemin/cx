<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Cx extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->view_folder = strtolower(__CLASS__) . '/';

        /* Connexion */
        if (!$this->ion_auth->logged_in() || !$this->session->userdata('loggedPdvId')) :
            redirect('secure/login');
        endif;
    }

    public function backup() {
        $this->load->dbutil();
        $prefs = array(
            'tables' => array(), // Array of tables to backup.
            'ignore' => array(), // List of tables to omit from the backup
            'format' => 'txt', // gzip, zip, txt
            'filename' => 'CIN.sql', // File name - NEEDED ONLY WITH ZIP FILES
            'add_drop' => true, // Whether to add DROP TABLE statements to backup file
            'add_insert' => true, // Whether to add INSERT data to backup file
            'newline' => "\n"                         // Newline character used in backup file
        );

        $backup = $this->dbutil->backup($prefs);
        write_file('../Sauvegardes/000.sql', $backup);
    }

    public function deconnexion() {
        $this->session->sess_destroy();
        $this->ion_auth->logout();
        redirect('secure');
        exit;
    }

    public function introuvable() {
        $data = array(
            'title' => 'Page introuvable',
            'description' => '',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function sessionInfos() {
        print_r($this->session->all_userdata());
        exit;
    }

    /**
     * Execute un fichier SQL
     * @param File $filesql Fichier en .sql
     * @return type
     */
    function executeQueryFile($filesql) {
        $query = file_get_contents($filesql);
        $array = explode(";\n", $query);
        $b = true;
        for ($i = 0; $i < count($array); $i++) {
            $str = $array[$i];
            if ($str != '') {
                $str .= ';';
                $b &= $this->db->query($str);
            }
        }

        return $b;
    }

    public function migration1() {

        $articles = $this->managerDevisarticles->liste();
        foreach ($articles as $a):
            $a->setArticlePrixNet(round($a->getArticlePrixUnitaire() * (100 - $a->getArticleRemise()) / 100, 2));
            $this->managerDevisarticles->editer($a);
        endforeach;
        $articlesBdc = $this->managerBdcarticles->liste();
        foreach ($articlesBdc as $a):
            $a->setArticlePrixNet(round($a->getArticlePrixUnitaire() * (100 - $a->getArticleRemise()) / 100, 2));
            $this->managerBdcarticles->editer($a);
        endforeach;
        //$this->executeQueryFile('migrateCertificate2.sql');
    }

    public function migration2() {

        //Supprime les devis non convertis de plus de 3 mois et les supprimés
        $devis = $this->managerDevis->liste();
        $limite = time() - 31536000;
        foreach ($devis as $d):
            if ($d->getDevisDelete() == 1 || ($d->getDevisDate() < $limite && $d->getDevisBdcId() == 0)):
                $this->managerDevis->delete($d);
            endif;
        endforeach;

        // On transforme tous les acomptes en reglements
        $acomptes = $this->managerAcomptes->liste();
        foreach ($acomptes as $a):
            $this->ajouterUnReglement($a);
        endforeach;
        //$this->executeQueryFile('migrateCertificate3.sql');
    }

    public function migration3() {

        // liste des Bdc à garder pour 3 cas
        // Les factures non soldées <- RESTENT DANS LA VERSION ANTERIEURE
        // les Bls non facturés
        // les Bdc non livrés
        $listeBdc = array();
        $bls = $this->managerBls->liste(array('blFactureId' => null));
        foreach ($bls as $b):
            if (!in_array($b->getBlBdcId(), $listeBdc)):
                $listeBdc[] = $b->getBlBdcId();
            endif;
        endforeach;

        $bdcs = $this->managerBdc->liste(array('bdcEtat < ' => 2));
        foreach ($bdcs as $bdc):
            if (!in_array($bdc->getBdcId(), $listeBdc)):
                $listeBdc[] = $bdc->getBdcId();
            endif;
        endforeach;

        /* Gestion des réglements */
        /* On conserve uniquement les réglements non attribués à des factures et  dont le Bdc n'est pas supprimé */
        $this->db->QUERY('DELETE FROM reglements WHERE reglementFactureId IS NOT NULL');
        $reglements = $this->managerReglements->liste(array('reglementFactureId' => null));
        foreach ($reglements as $r):
            $bdc = $this->managerBdc->getBdcById($r->getReglementBdcId());
            if ($bdc->getBdcDelete() == 1):
                $this->managerReglements->delete($r);
            endif;
        endforeach;

        /* On vide les Bdcs */
        $bdcFull = $this->managerBdc->listeFULL();
        foreach ($bdcFull as $bdc):
            if ($bdc->getBdcDelete(1) || !in_array($bdc->getBdcId(), $listeBdc)):
                $this->purgeBdc($bdc);
            endif;
        endforeach;
    }

    public function migration4() {
        $factures = $this->managerFactures->liste();
        foreach ($factures as $f):
            $this->managerFactures->delete($f);
        endforeach;

        /* Mise à jour des reglements */
        /* $reglements = $this->managerReglements->liste(); */
    }

    private function purgeBdc(Bdc $bdc) {

        /* Purge des BL */
        $bls = $this->managerBls->getBlByBdcId($bdc->getBdcId());
        if ($bls):
            foreach ($bls as $bl):
                $this->managerBls->delete($bl);
            endforeach;
        endif;

        /* Del du Bdc */
        $this->managerBdc->delete($bdc);

        /* DEL devis */
        $nbBdc = count($this->managerBdc->liste(array('bdcDevisId' => $bdc->getBdcDevisId())));
        if ($nbBdc <= 1):
            $this->managerDevis->delete($this->managerDevis->getDevisById($bdc->getBdcDevisId()));
        else:
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . 'Le devis ' . $bdc->getBdcDevisId() . ' est associé à ' . $nbBdc . ' BDCs');
        endif;
    }

    private function ajouterUnReglement(Acompte $acompte) {
        $this->db->trans_start();

        $bdc = $this->managerBdc->getBdcById($acompte->getAcompteBdcId());

        $dataReglement = array(
            'reglementDate' => $acompte->getAcompteDate(),
            'reglementType' => 1,
            'reglementBdcId' => $acompte->getAcompteBdcId(),
            'reglementFactureId' => $acompte->getAcompteFactureId(),
            'reglementClientId' => $bdc->getBdcClientId(),
            'reglementModeId' => $acompte->getAcompteModeReglementId(),
            'reglementRemiseId' => null,
            'reglementMontant' => $acompte->getAcompteTotal(),
            'reglementSourceId' => null,
            'reglementGroupeId' => null,
            'reglementUtile' => 1,
            'reglementToken' => ''
        );

        $reglement = new Reglement($dataReglement);
        $this->managerReglements->ajouter($reglement);
        $reglement->setReglementGroupeId($reglement->getReglementId());
        $this->managerReglements->editer($reglement);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE):
            return false;
        else:
            return $reglement;
        endif;
    }

}
