<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_bltva extends MY_model {

    protected $table = 'bltva';

    const classe = 'Bltva';

    public function ajouter(Bltva $tva) {
        $this->db
                ->set('tvaBlId', $tva->getTvaBlId())
                ->set('tvaTaux', $tva->getTvaTaux())
                ->set('tvaMontant', $tva->getTvaMontant())
                ->insert($this->table);
    }

    public function editer(Bltva $tva) {
        $this->db
                ->set('tvaMontant', $tva->getTvaMontant())
                ->where(array('tvaBlId' => $tva->getTvaBlId(), 'tvaTaux' => $tva->getTvaTaux()))
                ->update($this->table);
        return $this->db->affected_rows();
    }

    public function delete(Bltva $tva) {
        $this->db->where(array('tvaBlId' => $tva->getTvaBlId(), 'tvaTaux' => $tva->getTvaTaux()))
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function deleteTvaByBlId($blId) {
        $this->db->where('tvaBlId', $blId)
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function getTvaByBlId($blId, $type = 'object') {
        $query = $this->db->select('t.*')
                ->from($this->table . ' t')
                ->join('bl d', 'd.blId = t.tvaBlId', 'left')
                ->where('d.blPdvId', $this->session->userdata('loggedPdvId'))
                ->where('tvaBlId', $blId)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

}
