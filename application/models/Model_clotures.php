<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_clotures extends MY_model {

    protected $table = 'clotures';

    const classe = 'Cloture';

    /**
     * Ajout d'un objet de la classe Cloture à la BDD
     * @param Cloture $cloture Objet de la classe Cloture
     */
    public function ajouter(Cloture $cloture) {
        $this->db
                ->set('clotureDate', $cloture->getClotureDate())
                ->set('clotureType', $cloture->getClotureType())
                ->set('clotureMontant', $cloture->getClotureMontant())
                ->set('clotureToken', $cloture->getClotureToken())
                ->insert($this->table);
        $cloture->setClotureId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Cloture
     * @param Cloture $cloture Objet de la classe Cloture
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Cloture $cloture) {
        $this->db
                ->set('clotureDate', $cloture->getClotureDate())
                ->set('clotureType', $cloture->getClotureType())
                ->set('clotureMontant', $cloture->getClotureMontant())
                ->set('clotureToken', $cloture->getClotureToken())
                ->where('clotureId', $cloture->getClotureId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe cloture
     *
     * @param Cloture Objet de la classe Cloture
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Cloture $cloture) {
        $this->db->where('clotureId', $cloture->getClotureId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Retourne un array avec des Clotures correspondant aux critères su paralètre $where
     * @param integer $debut Debut des enregistrements sélectionnés
     * @param integer $nb Nombre d'enregistrements à retourner
     * @param array $where Critères de selection des clotures
     * @return array Liste d'objets de la classe Cloture
     */
    public function liste($where = array(), $tri = 'clotureDate ASC', $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getClotureJourByDate($date, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('clotureType', 1)
                ->where('clotureDate', $date)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    public function getClotureMoisByDate($date, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('clotureType', 2)
                ->where('clotureDate', $date)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    public function getClotureAnneeByDate($date, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('clotureType', 3)
                ->where('clotureDate', $date)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    /**
     * Retourne un objet de la classe Cloture correspondant à l'id
     * @param type $ref
     * @return \Cloture|boolean
     */
    public function getClotureById($clotureId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('clotureId', $clotureId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
