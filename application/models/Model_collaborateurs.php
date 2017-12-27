<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_collaborateurs extends MY_model {

    protected $table = 'collaborateurs';

    const classe = 'Collaborateur';

    /**
     * Ajout d'un objet de la classe Collaborateur à la BDD
     * @param Collaborateur $collaborateur Objet de la classe Collaborateur
     */
    public function ajouter(Collaborateur $collaborateur) {
        $this->db
                ->set('collaborateurPdvId', $collaborateur->getCollaborateurPdvId())
                ->set('collaborateurNom', $collaborateur->getCollaborateurNom())
                ->set('collaborateurActive', $collaborateur->getCollaborateurActive())
                ->insert($this->table);
        $collaborateur->setCollaborateurId($this->db->insert_id());
    }

    public function delete(Collaborateur $collaborateur) {
        $this->db->where('collaborateurId', $collaborateur->getCollaborateurCollaborateurId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function getCollaborateurById($collaborateurId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where(array('collaborateurId' => $collaborateurId))
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    public function liste($where = array(), $tri = 'collaborateurNom ASC', $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('collaborateurPdvId', $this->session->userdata('loggedPdvId'))
                ->where($where)
                ->order_by($tri)
                ->get();

        return $this->retourne($query, $type, self::classe);
    }

}
