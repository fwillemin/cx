<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Clients extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->view_folder = strtolower(__CLASS__) . '/';
        /* Connexion */
        /* Connexion */
        if (!$this->ion_auth->logged_in() || !$this->session->userdata('loggedPdvId')) :
            redirect('secure/login');
        endif;
    }

    public function index() {
        redirect('clients/liste');
        exit;
    }

    public function liste() {
        $data = array(
            'clients' => $this->managerClients->liste(),
            'modes' => $this->managerModesreglement->liste(),
            'conditions' => $this->managerConditionsreglement->liste(),
            'title' => 'CX - Gestion des clients',
            'description' => '',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function getAllClients() {
        echo json_encode($this->managerClients->liste(array(), 'clientNom ASC', 'array'));
    }

    public function clientSearch() {

        $this->form_validation->set_rules('clientSearch', 'Recherche', 'required|trim');
        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            //$clients = $this->managerClients->recherche( array('clientNom LIKE' => '%' . $this->input->post('clientSearch') . '%'), 'clientNom ASC', 'array');
            $clients = $this->managerClients->recherche($this->input->post('clientSearch'), 'clientNom ASC', 'array');
            echo json_encode(array('type' => 'success', 'clients' => $clients));
            exit;
        endif;
    }

    public function ficheClient($clientId = null) {
        if (!$clientId) :
            redirect('clients');
            exit;
        endif;

        $client = $this->managerClients->getClientById(intval($clientId));

        $data = array(
            'client' => $client,
            'modes' => $this->managerModesreglement->liste(),
            'conditions' => $this->managerConditionsreglement->liste(),
            'devis' => $this->managerDevis->liste(array('devisClientId' => $client->getClientId())),
            'bdcs' => $this->managerBdc->liste(array('bdcClientId' => $client->getClientId())),
            'factures' => $this->managerFactures->liste(array('factureClientId' => $client->getClientId())),
            'title' => 'CX - Fiche client',
            'description' => 'Fiche client',
            'keywords' => 'clients',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function addClient() {

        $this->form_validation->set_rules('addClientId', 'Id', 'is_natural_no_zero|trim');
        $this->form_validation->set_rules('addClientCodeComptable', 'Code comptable', 'trim');
        $this->form_validation->set_rules('addClientType', 'Type', 'numeric|trim');
        $this->form_validation->set_rules('addClientNom', 'Nom', 'required|trim');
        $this->form_validation->set_rules('addClientPrenom', 'Prénom', 'trim');
        $this->form_validation->set_rules('addClientAdresse1', 'Adresse', 'trim');
        $this->form_validation->set_rules('addClientAdresse2', 'Adresse Complement', 'trim');
        $this->form_validation->set_rules('addClientCp', 'Code postal', 'required|trim');
        $this->form_validation->set_rules('addClientVille', 'Ville', 'required|trim');
        $this->form_validation->set_rules('addClientPays', 'Pays', 'required|trim');
        $this->form_validation->set_rules('addClientTel', 'Téléphone', 'trim');
        $this->form_validation->set_rules('addClientPortable', 'Portable', 'trim');
        $this->form_validation->set_rules('addClientFax', 'Fax', 'trim');
        $this->form_validation->set_rules('addClientEmail', 'Email', 'valid_email|trim');
        $this->form_validation->set_rules('addClientIntracom', 'Intracommunautaire', 'trim');
        $this->form_validation->set_rules('addClientExo', 'Exoneration de TVA', 'required|in_list[0,1]');
        $this->form_validation->set_rules('addClientModeReglement', 'Mode de réglement', 'numeric|trim');
        $this->form_validation->set_rules('addClientConditionReglement', 'Conditions de réglement', 'numeric|trim');

        if ($this->form_validation->run()) :
            if ($this->input->post('addClientId')) :
                $client = $this->managerClients->getClientById(intval($this->input->post('addClientId')));
                if (empty($client)) :
                    echo json_encode(array('type' => 'error', 'message' => 'Client en cavale !'));
                    exit;
                else :
                    $client->setClientCodeComptable($this->input->post('addClientCodeComptable'));
                    $client->setClientType($this->input->post('addClientType'));
                    $client->setClientRaisonSociale($this->input->post('addClientRaisonSociale'));
                    $client->setClientNom($this->input->post('addClientNom'));
                    $client->setClientPrenom($this->input->post('addClientPrenom'));
                    $client->setClientAdresse1($this->input->post('addClientAdresse1'));
                    $client->setClientAdresse2($this->input->post('addClientAdresse2'));
                    $client->setClientCp($this->input->post('addClientCp'));
                    $client->setClientVille($this->input->post('addClientVille'));
                    $client->setClientPays($this->input->post('addClientPays'));
                    $client->setClientTel($this->input->post('addClientTel'));
                    $client->setClientPortable($this->input->post('addClientPortable'));
                    $client->setClientFax($this->input->post('addClientFax'));
                    $client->setClientEmail($this->input->post('addClientEmail'));
                    $client->setClientIntracom($this->input->post('addClientIntracom'));
                    $client->setClientExonerationTVA($this->input->post('addClientExo'));
                    $client->setClientModeReglementId($this->input->post('addClientModeReglement'));
                    $client->setClientConditionReglementId($this->input->post('addClientConditionReglement'));
                    $this->managerClients->editer($client);
                endif;
            else :
                $data = array(
                    'clientPdvId' => $this->session->userdata('loggedPdvId'),
                    'clientType' => $this->input->post('addClientType'),
                    'clientCodeComptable' => $this->input->post('addClientCodeComptable'),
                    'clientRaisonSociale' => strtoupper($this->input->post('addClientRaisonSociale')),
                    'clientNom' => strtoupper($this->input->post('addClientNom')),
                    'clientPrenom' => strtoupper($this->input->post('addClientPrenom')),
                    'clientAdresse1' => strtoupper($this->input->post('addClientAdresse1')),
                    'clientAdresse2' => strtoupper($this->input->post('addClientAdresse2')),
                    'clientCp' => $this->input->post('addClientCp'),
                    'clientVille' => $this->input->post('addClientVille'),
                    'clientPays' => $this->input->post('addClientPays'),
                    'clientTel' => $this->input->post('addClientTel'),
                    'clientPortable' => $this->input->post('addClientPortable'),
                    'clientFax' => $this->input->post('addClientFax'),
                    'clientEmail' => $this->input->post('addClientEmail'),
                    'clientIntracom' => $this->input->post('addClientIntracom'),
                    'clientExonerationTVA' => $this->input->post('addClientExo'),
                    'clientModeReglementId' => intval($this->input->post('addClientModeReglement')),
                    'clientConditionReglementId' => intval($this->input->post('addClientConditionReglement')),
                );

                $client = new Client($data);
                $this->managerClients->ajouter($client);
            endif;

            echo json_encode(array('type' => 'success'));
            exit;
        else :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
    }

    public function delClient() {
        $this->form_validation->set_rules('clientId', 'client', 'required|is_natural_no_zero|trim');
        if ($this->form_validation->run()) :
            /* recherche de bons de commandes sur ce client */
            $bdc = $this->m_bdc->liste(array('bdcClientId' => intval($this->input->post('clientId'))));
            if (count($bdc) > 0) :
                log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' : ' . 'Tentative de suppression d\'un client possedant un bon de commande');
                echo json_encode(array('type' => 'error', 'message' => 'Impossible de supprimer ce client, un bon de commande existe.'));
                exit;
            else :
                if ($this->m_client->delete(intval($this->input->post('clientId')))) :
                    /* On supprime les devis liés à ce client */
                    $devis = $this->m_devis->liste(array('devisClientId' => intval($this->input->post('clientId'))));
                    if (!empty($devis)) :
                        foreach ($devis as $d) :
                            $this->m_devisarticle->deleteDevisArticles(intval($d->devisId));
                            $this->m_devis->delete(intval($d->devisId));
                        endforeach;
                    endif;
                    echo json_encode(array('type' => 'success'));
                    exit;
                else :
                    log_error('error', __CLASS__ . '/' . __FUNCTION__ . ' : ' . 'Echec lors de la suppression d\'un client : clientId inconnu ou client etranger au pvdId.');
                    exit;
                endif;
            endif;
        else :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
    }

    public function getClient($session = null) {
        /* Si $session est défini on passe l'id client en variable de session */

        $this->form_validation->set_rules('clientId', 'client', 'required|is_natural_no_zero|trim');
        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            $client = $this->managerClients->getClientById(intval($this->input->post('clientId')), 'array');
            if (empty($client)) :
                echo json_encode(array('type' => 'error', 'message' => 'Client en cavale !'));
                exit;
            else :
                if ($session) :
                    $this->session->set_userdata(array('venteClientId' => $client['clientId'], 'venteClientType' => $client['clientType']));
                endif;
                echo json_encode(array('type' => 'success', 'client' => $client));
                exit;
            endif;
        endif;
    }

//        public function clientSearch(){
//            $this->form_validation->set_rules('clientSearch','Recherche','required|trim');
//            if($this->form_validation->run()):
//                echo json_encode(array('type' => 'success','clients'=>$this->m_client->liste(array('clientNom LIKE '=>$this->input->post('clientSearch').'%'))));
//                exit;
//            endif;
//        }
//        public function get_villes() { echo json_encode( $this->m_ville->liste(array('cp'=>$this->input->post('cp'))) ); }
}
