<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_avoirs extends MY_model {

    protected $table = 'avoirs';

    const classe = 'Avoir';

    /**
     * Ajout d'un objet de la classe Avoir à la BDD
     * @param Avoir $avoir Objet de la classe Avoir
     */
    public function ajouter(Avoir $avoir) {
        $this->db
                ->set('avoirPdvId', $avoir->getAvoirPdvId())
                ->set('avoirFactureId', $avoir->getAvoirFactureId())
                ->set('avoirDate', $avoir->getAvoirDate())
                ->set('avoirClientId', $avoir->getAvoirClientId())
                ->set('avoirTotalHT', $avoir->getAvoirTotalHT())
                ->set('avoirTotalTVA', $avoir->getAvoirTotalTVA())
                ->set('avoirTotalTTC', $avoir->getAvoirTotalTTC())
                ->insert($this->table);
        $avoir->setAvoirId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Avoir
     * @param Avoir $avoir Objet de la classe Avoir
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Avoir $avoir) {
        $this->db
                ->set('avoirPdvId', $avoir->getAvoirPdvId())
                ->set('avoirFactureId', $avoir->getAvoirFactureId())
                ->set('avoirDate', $avoir->getAvoirDate())
                ->set('avoirClientId', $avoir->getAvoirClientId())
                ->set('avoirTotalHT', $avoir->getAvoirTotalHT())
                ->set('avoirTotalTVA', $avoir->getAvoirTotalTVA())
                ->set('avoirTotalTTC', $avoir->getAvoirTotalTTC())
                ->where('avoirId', $avoir->getAvoirId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    public function liste($where = array(), $type = 'object') {
        $query = $this->db->select('f.*')
                ->from($this->table . ' f')
                ->where('f.avoirPdvId', $this->session->userdata('loggedPdvId'))
                ->where($where)
                ->group_by('f.avoirId')
                ->order_by('f.avoirDate DESC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getAvoirById($avoirId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('avoirPdvId', $this->session->userdata('loggedPdvId'))
                ->where('avoirId', $avoirId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    /**
     *  retourne une liste de avoirs pour une facture
     */
    function getAvoirsByFactureId($factureId, $type = 'object') {
        $query = $this->db->select('a.*')
                ->from($this->table . ' a')
                ->join('factures f', 'f.factureId = a.avoirFactureId', 'left')
                ->where('a.avoirPdvId', $this->session->userdata('loggedPdvId'))
                ->where('f.factureId', $factureId)
                ->order_by('a.avoirDate DESC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

}
