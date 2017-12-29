<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_bdctva extends MY_model {

    protected $table = 'bdctva';

    const classe = 'Bdctva';

    public function ajouter(Bdctva $tva) {
        $this->db
                ->set('tvaBdcId', $tva->getTvaBdcId())
                ->set('tvaTaux', $tva->getTvaTaux())
                ->set('tvaMontant', $tva->getTvaMontant())
                ->insert($this->table);
    }

    public function editer(Bdctva $tva) {
        $this->db
                ->set('tvaMontant', $tva->getTvaMontant())
                ->where(array('tvaBdcId' => $tva->getTvaBdcId(), 'tvaTaux' => $tva->getTvaTaux()))
                ->update($this->table);
        return $this->db->affected_rows();
    }

    public function delete(Bdctva $tva) {
        $this->db->where(array('tvaBdcId' => $tva->getTvaBdcId(), 'tvaTaux' => $tva->getTvaTaux()))
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function deleteTvaByBdcId($bdcId) {
        $this->db->where('tvaBdcId', $bdcId)
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function getTvaByBdcId($bdcId, $type = 'object') {
        $query = $this->db->select('t.*')
                ->from($this->table . ' t')
                ->join('bdc d', 'd.bdcId = t.tvaBdcId', 'left')
                ->where('d.bdcPdvId', $this->session->userdata('loggedPdvId'))
                ->where('tvaBdcId', $bdcId)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

}
