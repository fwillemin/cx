<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Settings extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->view_folder = strtolower(__CLASS__) . '/';

        /* Connexion */
        if (!$this->ion_auth->logged_in() || !$this->session->userdata('loggedPdvId')) :
            redirect('secure/login');
        endif;
    }

    public function index() {
        $data = array(
            'pdv' => $this->managerPdv->getPdvById($this->session->userdata('loggedPdvId')),
            'title' => 'Paramètrages',
            'description' => 'Paramètrez votre CX',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function majPdv() {

        if ($this->form_validation->run('majPdv')):

            $pdv = $this->managerPdv->getPdvById($this->session->userdata('loggedPdvId'));
            $pdv->setPdvNomCommercial($this->input->post('modPdvNomCommercial'));
            $pdv->setPdvSiren($this->input->post('modPdvSiren'));
            $pdv->setPdvApe($this->input->post('modPdvApe'));
            $pdv->setPdvTvaIntracom($this->input->post('modPdvTvaIntracom'));
            $pdv->setPdvAdresse1($this->input->post('modPdvAdresse1'));
            $pdv->setPdvAdresse2($this->input->post('modPdvAdresse2'));
            $pdv->setPdvCp($this->input->post('modPdvCp'));
            $pdv->setPdvVille($this->input->post('modPdvVille'));
            $pdv->setPdvTelephone($this->input->post('modPdvTelephone'));
            $pdv->setPdvEmail($this->input->post('modPdvEmail'));
            $pdv->setPdvFax($this->input->post('modPdvFax'));
            $pdv->setPdvWww($this->input->post('modPdvWww'));
            $pdv->setPdvTelephoneCommercial($this->input->post('modPdvTelephoneCommercial'));
            $pdv->setPdvTelephoneTechnique($this->input->post('modPdvTelephoneTechnique'));
            $pdv->setPdvEmailTechnique($this->input->post('modPdvEmailTechnique'));
            $pdv->setPdvEmailCommercial($this->input->post('modPdvEmailCommercial'));
            $this->managerPdv->editer($pdv);
            echo json_encode(array('type' => 'success'));
        else:
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        endif;
        exit;
    }

}
