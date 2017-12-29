<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_devistva extends MY_model {

    protected $table = 'devistva';

    const classe = 'Devistva';

    public function ajouter(Devistva $tva) {
        $this->db
                ->set('tvaDevisId', $tva->getTvaDevisId())
                ->set('tvaTaux', $tva->getTvaTaux())
                ->set('tvaMontant', $tva->getTvaMontant())
                ->insert($this->table);
    }

    public function editer(Devistva $tva) {
        $this->db
                ->set('tvaMontant', $tva->getTvaMontant())
                ->where(array('tvaDevisId' => $tva->getTvaDevisId(), 'tvaTaux' => $tva->getTvaTaux()))
                ->update($this->table);
        return $this->db->affected_rows();
    }

    public function delete(Devistva $tva) {
        $this->db->where(array('tvaDevisId' => $tva->getTvaDevisId(), 'tvaTaux' => $tva->getTvaTaux()))
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function deleteTvaByDevisId($devisId) {
        $this->db->where('tvaDevisId', $devisId)
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function getTvaByDevisId($devisId, $type = 'object') {
        $query = $this->db->select('t.*')
                ->from($this->table . ' t')
                ->join('devis d', 'd.devisId = t.tvaDevisId', 'left')
                ->where('d.devisPdvId', $this->session->userdata('loggedPdvId'))
                ->where('tvaDevisId', $devisId)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

}
