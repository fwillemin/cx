<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_usines extends CI_Model {

    protected $table = 'usines';

    /**
     * Ajout d'un objet de la classe Usine à la BDD
     * @param Usine $usine Objet de la classe Usine
     */
    public function ajouter(Usine $usine) {
        $this->db
                ->set('usinePdvId', $usine->getUsinePdvId())
                ->set('usineNom', $usine->getUsineNom())
                ->set('usineEmail', $usine->getUsineEmail())
                ->set('usineCodeClient', $usine->getUsineCodeClient())
                ->insert($this->table);
        $usine->setUsineId($this->db->insert_id());
    }

    public function editer(Usine $usine) {
        $this->db
                ->set('usinePdvId', $usine->getUsinePdvId())
                ->set('usineNom', $usine->getUsineNom())
                ->set('usineEmail', $usine->getUsineEmail())
                ->set('usineCodeClient', $usine->getUsineCodeClient())
                ->where('usineId', $usine->getUsineId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe usine
     *
     * @param Usine Objet de la classe Usine
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Usine $usine) {
        $this->db->where('usineId', $usine->getUsineId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function liste($where = array(), $retour = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('usinePdvId', $this->session->userdata('loggedPdvId'))
                ->where($where)
                ->order_by('usineNom', 'ASC')
                ->get();

        if ($query->num_rows() > 0):
            foreach ($query->result() AS $row):
                if ($retour == 'object'):
                    $usines[] = new Usine((array) $row);
                else:
                    $usines[] = (array) $row;
                endif;
            endforeach;
            return $usines;
        else:
            return FALSE;
        endif;
    }

    public function getUsineById($usineId, $type = 'object') {
        $query = $this->db->select('u.*')
                ->from('usines u')
                ->where(array('u.usineId' => intval($usineId)))
                ->get();
        if ($query->num_rows() > 0):
            if ($type == 'object') {
                $usine = new Usine((array) $query->row());
            } else {
                $usine = (array) $query->row();
            }
            return $usine;
        else:
            return FALSE;
        endif;
    }

}
