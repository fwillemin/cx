<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_remises extends CI_Model {

    protected $table = 'remisecheques';

    /**
     * Ajout d'un objet de la classe Remise à la BDD
     * @param Remise $remise Objet de la classe Remise
     */
    public function ajouter(Remise $remise) {
        $this->db
                ->set('remisePdvId', $remise->getRemisePdvId())
                ->set('remiseDate', $remise->getRemiseDate())
                ->set('remiseTotal', $remise->getRemiseTotal())
                ->set('remiseBanque', $remise->getRemiseBanque())
                ->insert($this->table);
        $remise->setRemiseId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Remise
     * @param Remise $remise Objet de la classe Remise
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Remise $remise) {
        $this->db
                ->set('remisePdvId', $remise->getRemisePdvId())
                ->set('remiseDate', $remise->getRemiseDate())
                ->set('remiseTotal', $remise->getRemiseTotal())
                ->set('remiseBanque', $remise->getRemiseBanque())
                ->where('remiseId', $remise->getRemiseId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe remise
     *
     * @param Remise Objet de la classe Remise
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Remise $remise) {
        $this->db->where('remiseId', $remise->getRemiseId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function liste($where = array(), $tri = 'r.remiseDate ASC', $retour = 'object') {
        $query = $this->db->select('r.*')
                ->from($this->table . ' r')
                ->where('r.remisePdvId', $this->session->userdata('loggedPdvId'))
                ->where($where)
                ->order_by($tri)
                ->get();

        if ($query->num_rows() > 0):
            foreach ($query->result() AS $row):
                if ($retour == 'object'):
                    $remises[] = new Remise((array) $row);
                else:
                    $remises[] = (array) $row;
                endif;
            endforeach;
            return $remises;
        else:
            return FALSE;
        endif;
    }

    public function getRemiseById($remiseId, $type = 'object') {
        $query = $this->db->select('r.*')
                ->from($this->table . ' r')
                ->where('r.remisePdvId', $this->session->userdata('loggedPdvId'))
                ->where('r.remiseId', intval($remiseId))
                ->get();

        if ($query->num_rows() > 0):
            if ($type == 'object'):
                $remise = new Remise((array) $query->row());
            else:
                $remise = (array) $query->row();
            endif;
            return $remise;
        else:
            return FALSE;
        endif;
    }

}
