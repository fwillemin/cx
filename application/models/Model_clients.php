<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_clients extends CI_Model {

    protected $table = 'clients';

    /**
     * Ajout d'un objet de la classe Client à la BDD
     * @param Client $client Objet de la classe Client
     */
    public function ajouter(Client $client) {
        $this->db
                ->set('clientPdvId', $client->getClientPdvId())
                ->set('clientType', $client->getClientType())
                ->set('clientCodeComptable', $client->getClientCodeComptable())
                ->set('clientRaisonSociale', $client->getClientRaisonSociale())
                ->set('clientNom', $client->getClientNom())
                ->set('clientPrenom', $client->getClientPrenom())
                ->set('clientAdresse1', $client->getClientAdresse1())
                ->set('clientAdresse2', $client->getClientAdresse2())
                ->set('clientCp', $client->getClientCp())
                ->set('clientVille', $client->getClientVille())
                ->set('clientPays', $client->getClientPays())
                ->set('clientTel', $client->getClientTel())
                ->set('clientPortable', $client->getClientPortable())
                ->set('clientFax', $client->getClientFax())
                ->set('clientEmail', $client->getClientEmail())
                ->set('clientIntracom', $client->getClientIntracom())
                ->set('clientExonerationTVA', $client->getClientExonerationTVA())
                ->set('clientModeReglementId', $client->getClientModeReglementId())
                ->set('clientConditionReglementId', $client->getClientConditionReglementId())
                ->insert($this->table);
        $client->setClientId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Client
     * @param Client $client Objet de la classe Client
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Client $client) {
        $this->db
                ->set('clientPdvId', $client->getClientPdvId())
                ->set('clientType', $client->getClientType())
                ->set('clientCodeComptable', $client->getClientCodeComptable())
                ->set('clientRaisonSociale', $client->getClientRaisonSociale())
                ->set('clientNom', $client->getClientNom())
                ->set('clientPrenom', $client->getClientPrenom())
                ->set('clientAdresse1', $client->getClientAdresse1())
                ->set('clientAdresse2', $client->getClientAdresse2())
                ->set('clientCp', $client->getClientCp())
                ->set('clientVille', $client->getClientVille())
                ->set('clientPays', $client->getClientPays())
                ->set('clientTel', $client->getClientTel())
                ->set('clientPortable', $client->getClientPortable())
                ->set('clientFax', $client->getClientFax())
                ->set('clientEmail', $client->getClientEmail())
                ->set('clientIntracom', $client->getClientIntracom())
                ->set('clientExonerationTVA', $client->getClientExonerationTVA())
                ->set('clientModeReglementId', $client->getClientModeReglementId())
                ->set('clientConditionReglementId', $client->getClientConditionReglementId())
                ->where('clientId', $client->getClientId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe client
     *
     * @param Client Objet de la classe Client
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Client $client) {
        $this->db->where('clientId', $client->getClientId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function liste($where = array(), $tri = 'c.clientNom ASC', $retour = 'object') {
        $query = $this->db->select('c.*, r.conditionReglementNom AS clientConditionReglement, m.modeReglementNom AS clientConditionReglement')
                ->from('clients c')
                ->where('c.clientPdvId', $this->session->userdata('loggedPdvId'))
                ->join('conditionsreglement r', 'r.conditionReglementId = c.clientConditionReglementId', 'left')
                ->join('modesreglement m', 'm.modeReglementId = c.clientModeReglementId', 'left')
                ->where($where)
                ->order_by($tri)
                ->get();
        if ($query->num_rows() > 0):
            foreach ($query->result() AS $row):
                if ($retour == 'object'):
                    $clients[] = new Client((array) $row);
                else:
                    $clients[] = (array) $row;
                endif;
            endforeach;
            return $clients;
        else:
            return FALSE;
        endif;
    }

    public function recherche($chaine, $tri = 'c.clientNom ASC', $retour = 'object') {
        $query = $this->db->select('c.*, r.conditionReglementNom AS clientConditionReglement, m.modeReglementNom AS clientConditionReglement')
                ->from('clients c')
                ->where('c.clientPdvId', $this->session->userdata('loggedPdvId'))
                ->join('conditionsreglement r', 'r.conditionReglementId = c.clientConditionReglementId', 'left')
                ->join('modesreglement m', 'm.modeReglementId = c.clientModeReglementId', 'left')
                ->where('clientNom LIKE ', '%' . $chaine . '%')
                ->or_where('clientRaisonSociale LIKE ', '%' . $chaine . '%')
                ->order_by($tri)
                ->get();
        if ($query->num_rows() > 0):
            foreach ($query->result() AS $row):
                if ($retour == 'object'):
                    $clients[] = new Client((array) $row);
                else:
                    $clients[] = (array) $row;
                endif;
            endforeach;
            return $clients;
        else:
            return FALSE;
        endif;
    }

    public function getClientById($clientId, $type = 'object') {
        $query = $this->db->select('c.*, r.conditionReglementNom AS clientConditionReglement, m.modeReglementNom AS clientModeReglement')
                ->from('clients c')
                ->join('conditionsreglement r', 'r.conditionReglementId = c.clientConditionReglementId', 'left')
                ->join('modesreglement m', 'm.modeReglementId = c.clientModeReglementId', 'left')
                ->where('c.clientId', intval($clientId))
                ->get();
        if ($query->num_rows() > 0):
            if ($type == 'object'):
                $client = new Client((array) $query->row());
            else:
                $client = (array) $query->row();
            endif;
            return $client;
        else:
            return FALSE;
        endif;
    }

    public function listeMigration() {
        $query = $this->db->select('*')
                ->from('clients c')
                ->get();
        if ($query->num_rows() > 0):
            foreach ($query->result() AS $row):
                $clients[] = (array) $row;
            endforeach;
            return $clients;
        else:
            return FALSE;
        endif;
    }

}
