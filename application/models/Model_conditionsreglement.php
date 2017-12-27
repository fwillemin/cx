<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_conditionsreglement extends CI_Model {

    protected $table = 'conditionsreglement';

    /**
     * Ajout d'un objet de la classe Conditionreglement à la BDD
     * @param Conditionreglement $conditionreglement Objet de la classe Conditionreglement
     */
    public function ajouter(Conditionreglement $conditionreglement) {
        $this->db
                ->set('conditionReglementNom', $conditionreglement->getConditionreglementNom())
                ->insert($this->table);
        $conditionreglement->setConditionreglementId($this->db->insert_id());
    }

    public function liste($where = array(), $retour = 'object') {
        $query = $this->db->select('m.*')
                ->from($this->table . ' m')
                ->where($where)
                ->order_by('m.conditionReglementId ASC')
                ->get();

        if ($query->num_rows() > 0):
            foreach ($query->result() AS $row):
                if ($retour == 'object'):
                    $conds[] = new Conditionreglement((array) $row);
                else:
                    $conds[] = (array) $row;
                endif;
            endforeach;
            return $conds;
        else:
            return FALSE;
        endif;
    }

    public function getConditionReglementById($conditionreglementId) {
        $query = $this->db->select('m.*')
                ->from('conditionsreglement m')
                ->where(array('m.conditionreglementId' => intval($conditionreglementId)))
                ->get();
        if ($query->num_rows() > 0):
            $conditionreglement = new Conditionreglement((array) $query->row());
            return $conditionreglement;
        else:
            return FALSE;
        endif;
    }

}
