<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_bls extends MY_model {

    protected $table = 'bl';

    const classe = 'Bl';

    public function ajouter(Bl $bl) {
        $this->db
                ->set('blPdvId', $bl->getBlPdvId())
                ->set('blBdcId', $bl->getBlBdcId())
                ->set('blDate', $bl->getBlDate())
                ->set('blFactureId', $bl->getBlFactureId())
                ->set('blDelete', $bl->getBlDelete())
                ->insert($this->table);
        $bl->setBlId($this->db->insert_id());
    }

    public function editer(Bl $bl) {
        $this->db
                ->set('blPdvId', $bl->getBlPdvId())
                ->set('blBdcId', $bl->getBlBdcId())
                ->set('blDate', $bl->getBlDate())
                ->set('blFactureId', $bl->getBlFactureId())
                ->set('blDelete', $bl->getBlDelete())
                ->where('blId', $bl->getBlId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    public function delete(Bl $bl) {
        $this->db->where('blId', $bl->getBlId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function liste($where = array(), $tri = 'b.blDate DESC', $type = 'object') {
        $query = $this->db->select('b.*, c.clientId as blClientId')
                ->from($this->table . ' b')
                ->join('livraisons l', 'l.livraisonBlId = b.blId')
                ->join('bdc bdc', 'bdc.bdcId = b.blBdcId', 'left')
                ->join('clients c', 'c.clientId = bdc.bdcClientId', 'left')
                ->where(array('b.blPdvId' => $this->session->userdata('loggedPdvId')))
                ->where($where)
                ->group_by('b.blId')
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getBlById($blId, $type = 'object') {
        $query = $this->db->select('b.*, c.clientId as blClientId')
                ->from($this->table . ' b')
                ->where('b.blPdvId', $this->session->userdata('loggedPdvId'))
                ->join('bdc bdc', 'bdc.bdcId = b.blBdcId', 'left')
                ->join('clients c', 'c.clientId = bdc.bdcClientId', 'left')
                ->where('b.blId', $blId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    public function getBlByBdcId($bdcId, $type = 'object') {
        $query = $this->db->select('b.*')
                ->from($this->table . ' b')
                ->join('bdc bdc', 'bdc.bdcId = b.blBdcId')
                ->where(array('b.blPdvId' => $this->session->userdata('loggedPdvId'), 'b.blDelete' => 0))
                ->where('bdc.bdcId', $bdcId)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getBlByFactureId($factureId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('blPdvId', $this->session->userdata('loggedPdvId'))
                ->where('blFactureId', $factureId)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

}
