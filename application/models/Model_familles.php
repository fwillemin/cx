<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_familles extends CI_Model {

    protected $table = 'familles';

    /**
     * Ajout d'un objet de la classe Famille à la BDD
     * @param Famille $famille Objet de la classe Famille
     */
    public function ajouter(Famille $famille) {
        $this->db
                ->set('famillePdvId', $famille->getFamillePdvId())
                ->set('familleNom', $famille->getFamilleNom())
                ->insert($this->table);
        $famille->setFamilleId($this->db->insert_id());
    }

    public function editer(Famille $famille) {
        $this->db
                ->set('famillePdvId', $famille->getFamillePdvId())
                ->set('familleNom', $famille->getFamilleNom())
                ->where('familleId', $famille->getFamilleId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe famille
     *
     * @param Famille Objet de la classe Famille
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Famille $famille) {
        $this->db->where('familleId', $famille->getFamilleId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function liste($where = array(), $retour = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('famillePdvId', $this->session->userdata('loggedPdvId'))
                ->where($where)
                ->order_by('familleNom', 'ASC')
                ->get();

        if ($query->num_rows() > 0):
            foreach ($query->result() AS $row):
                if ($retour == 'object'):
                    $familles[] = new Famille((array) $row);
                else:
                    $familles[] = (array) $row;
                endif;
            endforeach;
            return $familles;
        else:
            return FALSE;
        endif;
    }

    public function getFamilleById($familleId, $type = 'object') {
        $query = $this->db->select('f.*')
                ->from('familles f')
                ->where(array('f.familleId' => intval($familleId)))
                ->get();
        if ($query->num_rows() > 0):
            if ($type == 'object') {
                $famille = new Famille((array) $query->row());
            } else {
                $famille = (array) $query->row();
            }
            return $famille;
        else:
            return FALSE;
        endif;
    }

}
