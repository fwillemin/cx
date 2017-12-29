<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_acomptes extends CI_Model {

    protected $table = 'acomptes';

    /**
     * Ajout d'un objet de la classe Acompte à la BDD
     * @param Acompte $acompte Objet de la classe Acompte
     */
    public function ajouter(Acompte $acompte) {
        $this->db
                ->set('acompteBdcId', $acompte->getAcompteBdcId())
                ->set('acompteFactureId', $acompte->getAcompteFactureId())
                ->set('acompteDate', $acompte->getAcompteDate())
                ->set('acompteTotal', $acompte->getAcompteTotal())
                ->set('acompteModeReglementId', $acompte->getAcompteModeReglementId())
                ->insert($this->table);
        $acompte->setAcompteId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Acompte
     * @param Acompte $acompte Objet de la classe Acompte
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Acompte $acompte) {
        $this->db
                ->set('acompteBdcId', $acompte->getAcompteBdcId())
                ->set('acompteFactureId', $acompte->getAcompteFactureId())
                ->set('acompteDate', $acompte->getAcompteDate())
                ->set('acompteTotal', $acompte->getAcompteTotal())
                ->set('acompteModeReglementId', $acompte->getAcompteModeReglementId())
                ->where('acompteId', $acompte->getAcompteId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe acompte
     *
     * @param Acompte Objet de la classe Acompte
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Acompte $acompte) {
        $this->db->where('acompteId', $acompte->getAcompteId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function liste($where = array(), $tri = 'acompteDate DESC', $retour = 'object') {
        $query = $this->db->select('a.*')
                ->from($this->table . ' a')
                ->where($where)
                ->order_by($tri)
                ->get();

        if ($query->num_rows() > 0):
            foreach ($query->result() AS $row):
                if ($retour == 'object'):
                    $acomptes[] = new Acompte((array) $row);
                else:
                    $acomptes[] = (array) $row;
                endif;
            endforeach;
            return $acomptes;
        else:
            return FALSE;
        endif;
    }

    public function getAcomptesByFactureId(Facture $facture, $retour = 'object') {
        $query = $this->db->select('a.*')
                ->from($this->table . ' a')
                ->where('a.acompteFactureId', $facture->getFactureId())
                ->order_by('a.acompteDate DESC')
                ->get();

        if ($query->num_rows() > 0):
            foreach ($query->result() AS $row):
                if ($retour == 'object'):
                    $acomptes[] = new Acompte((array) $row);
                else:
                    $acomptes[] = (array) $row;
                endif;
            endforeach;
            return $acomptes;
        else:
            return FALSE;
        endif;
    }

    public function getAcomptesByBdcId($bdcId, $retour = 'object') {
        $query = $this->db->select('a.*')
                ->from($this->table . ' a')
                ->where('a.acompteBdcId', $bdcId)
                ->order_by('a.acompteDate DESC')
                ->get();

        if ($query->num_rows() > 0):
            foreach ($query->result() AS $row):
                if ($retour == 'object'):
                    $acomptes[] = new Acompte((array) $row);
                else:
                    $acomptes[] = (array) $row;
                endif;
            endforeach;
            return $acomptes;
        else:
            return FALSE;
        endif;
    }

    public function getAcompteById($acompteId, $type = 'object') {
        $query = $this->db->select('a.*')
                ->from($this->table . ' a')
                ->where('a.acompteId', $acompteId)
                ->get();
        if ($query->num_rows() > 0):
            if ($type == 'object'):
                $acompte = new Acompte((array) $query->row());
            else:
                $acompte = (array) $query->row();
            endif;
            return $acompte;
        else:
            return FALSE;
        endif;
    }

}
