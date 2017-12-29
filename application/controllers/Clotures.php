<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class clotures extends CI_Controller {

    //const tauxTVA = 0.2;
    const anneeBase = 2017;

    public function __construct() {

        parent::__construct();
        $this->view_folder = strtolower(__CLASS__) . '/';

        if (!$this->ion_auth->logged_in()) :
            redirect('secure/login');
        endif;
    }

    public function liste($annee = null, $mois = null, $jour = null) {

        $where = array();
        if ($annee):
            if (!$mois):
                $where['clotureDate >='] = mktime(0, 0, 0, 01, 01, $annee);
                $where['clotureDate <='] = mktime(23, 59, 59, 12, 31, $annee);
                $where['clotureType'] = 2;
            else:
                $where['clotureDate >='] = mktime(0, 0, 0, $mois, 01, $annee);
                $where['clotureDate <='] = mktime(23, 59, 59, $mois, date('t', mktime(0, 0, 0, $mois, 1, $annee)), $annee);
                $where['clotureType'] = 1;
            endif;
        else:
            $where['clotureType'] = 3;
        endif;
        $clotures = $this->managerClotures->liste($where);
        //log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . print_r($clotures, 1));

        if ($jour):
            $ventes = $this->managerVentes->liste(array('venteEtat' => 2, 'venteDate >=' => mktime(0, 0, 0, $mois, $jour, $annee), 'venteDate <=' => mktime(23, 59, 59, $mois, $jour, $annee)));
            if (!empty($ventes)):
                foreach ($ventes as $v)
                    $v->hydrateReglement();
            endif;
        else:
            $ventes = null;
        endif;

        $data = array(
            'clotures' => $clotures,
            'ventes' => $ventes,
            'anneeBase' => self::anneeBase,
            'title' => 'Look&Soins',
            'description' => '',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    /* Cloture une journée, un mois et une année */

    public function cloture($time = null) {

        if (!$time):
            $time = time();
        endif;

        $this->clotureJour(date('Y-m-d', $time));
        if (date('d', $time) == date('t', $time)):
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . 'Initialisation de la cloture mensuelle...');
            $this->clotureMois(date('m', $time), date('Y', $time));
        endif;
        if (date('d-m-Y', $time) == date('31-12-Y', $time)):
            $this->clotureAnnee(date('Y', $time));
        endif;
    }

    private function clotureJour($jour) {

        $debut = $this->letslib->mktimeFromInputDate($jour, 0, 0, 0);
        $fin = $this->letslib->mktimeFromInputDate($jour, 23, 59, 59);

        $exist = $this->managerClotures->getClotureJourByDate($fin);
        if ($exist):
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . 'Il y a déjà une clôture journalière pour cette date.');

        else:

            $totalJour = 0;
            $ventes = $this->managerVentes->liste(array('venteEtat' => 2, 'venteDate >=' => $debut, 'venteDate <=' => $fin));
            if ($ventes):
                foreach ($ventes as $v):
                    $totalJour += $v->getVenteTotalTTC();
                endforeach;
            endif;

            $avoirs = $this->managerAvoirs->liste(array('avoirDate >=' => $debut, 'avoirDate <=' => $fin));
            if ($avoirs):
                foreach ($avoirs as $a):
                    $totalJour -= $a->getAvoirTotalTTC();
                endforeach;
            endif;

            $chaine = $fin . number_format(floatval($totalJour), 2, '.', '') . '1';
            $secure = new Token();
            $token = $secure->getToken($chaine);

            $this->addCloture($fin, $totalJour, 1, $token);
        endif;
    }

    private function clotureMois($mois, $annee) {

        $debut = mktime(0, 0, 0, $mois, 1, $annee);
        $fin = mktime(23, 59, 59, $mois, date('t', $debut), $annee);

        $exist = $this->managerClotures->getClotureMoisByDate($fin);
        if ($exist):
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . 'Il y a déjà une clôture mensuelle pour cette date.');

        else:

            $totalMois = 0;
            $clotures = $this->managerClotures->liste(array('clotureType' => 1, 'clotureDate >=' => $debut, 'clotureDate <=' => $fin));
            if ($clotures):
                foreach ($clotures as $c):
                    $totalMois += $c->getClotureMontant();
                endforeach;
            endif;

            $chaine = $fin . number_format(floatval($totalMois), 2, '.', '') . '2';
            $secure = new Token();
            $token = $secure->getToken($chaine);

            $this->addCloture($fin, $totalMois, 2, $token);
        endif;
    }

    private function clotureAnnee($annee) {

        $debut = mktime(0, 0, 0, 1, 1, $annee);
        $fin = mktime(23, 59, 59, 12, 31, $annee);

        $exist = $this->managerClotures->getClotureAnneeByDate($fin);
        if ($exist):
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . 'Il y a déjà une clôture annuelle pour cette date.');

        else:

            $totalAnnee = 0;
            $clotures = $this->managerClotures->liste(array('clotureType' => 2, 'clotureDate >=' => $debut, 'clotureDate <=' => $fin));
            if ($clotures):
                foreach ($clotures as $c):
                    $totalAnnee += $c->getClotureMontant();
                endforeach;
            endif;

            $chaine = $fin . number_format(floatval($totalAnnee), 2, '.', '') . '2';
            $secure = new Token();
            $token = $secure->getToken($chaine);

            $this->addCloture($fin, $totalAnnee, 3, $token);
        endif;
    }

    private function addCloture($date, $total, $type, $token) {

        $this->db->trans_start();

        $dataCloture = array(
            'clotureDate' => $date,
            'clotureMontant' => $total,
            'clotureType' => $type,
            'clotureToken' => $token
        );

        $cloture = new Cloture($dataCloture);
        $this->managerClotures->ajouter($cloture);

        $this->db->trans_complete();
        if ($this->db->trans_status() === false):
            $this->letslib->emailEchecCloture();
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' => ' . 'Erreur dans la cloture journalière du ' . $date);
            return false;
        else:
            switch ($type):
                case 1:
                    $this->letslib->emailClotureJour($cloture);
                    break;
                case 2:
                    $this->letslib->emailClotureMois($cloture);
                    break;
                case 3:
                    $this->letslib->emailClotureAnnee($cloture);
                    break;
            endswitch;

            return $cloture;
        endif;
    }

}
