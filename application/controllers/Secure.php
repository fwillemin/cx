<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Secure extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__);

        if ($this->ion_auth->logged_in() && $this->session->userdata('loggedPdvId')) :
            redirect('ventes/bdcListe');
            exit;
        endif;
    }

    /**
     * page de login
     */
    public function login() {
        $data = array(
            'title' => 'Connexion à CX',
            'description' => 'Saississez vos identifiants pour accèder à CX.',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function tryLogin() {

        if (!$this->form_validation->run('identification')) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            /* On teste la demande de connexion */
            if ($this->ion_auth->login($this->input->post('login'), $this->input->post('pass'), 0)) :
                echo json_encode(array('type' => 'success'));
            else :
                log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' MAUVAIS ID DE CONNEXION');
                log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' ' . $this->input->post('login'));
                log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' ' . $this->input->post('pass'));
                echo json_encode(array('type' => 'error', 'message' => 'Identifiants de connexion invalides.'));
            endif;
        endif;
        exit;
    }

    /* cin = import59
     * max = max&isa59
     */

//    public function addUser() {
//
//        $email = 'maxime@carreauximportnegoce.fr';
//        $identity = 'max';
//        $password = 'max&isa59';
//
//        $additional_data = array(
//            'first_name' => 'Maxime',
//            'last_name' => 'LEDIEU',
//            'company' => 'CARREAUX IMPORT NEGOCE',
//            'phone' => '0651731808',
//            'pdvId' => 1
//        );
//
//        /* Admin */
//        $group = array('1');
//
//        $this->ion_auth->register($identity, $password, $email, $additional_data, $group);
//    }
}
