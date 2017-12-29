<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_avoirlignes extends MY_model {

    protected $table = 'avoirlignes';

    const classe = 'Avoirligne';

    /**
     * Ajout d'un objet de la classe Avoirligne à la BDD
     * @param Avoirligne $ligne Objet de la classe Ligne
     */
    public function ajouter(AvoirLigne $ligne) {
        $this->db
                ->set('ligneAvoirId', $ligne->getLigneAvoirId())
                ->set('ligneProduitId', $ligne->getLigneProduitId())
                ->set('ligneUniteId', $ligne->getLigneUniteId())
                ->set('ligneDesignation', $ligne->getLigneDesignation())
                ->set('ligneQte', $ligne->getLigneQte())
                ->set('lignePrixUnitaire', $ligne->getLignePrixUnitaire())
                ->set('ligneTauxTVA', $ligne->getLigneTauxTVA())
                ->set('ligneRemise', $ligne->getLigneRemise())
                ->set('lignePrixNet', $ligne->getLignePrixNet())
                ->set('ligneTotalHT', $ligne->getLigneTotalHT())
                ->insert($this->table);
        $ligne->setLigneId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Ligne
     * @param Ligne $ligne Objet de la classe Ligne
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Avoirligne $ligne) {
        $this->db
                ->set('ligneAvoirId', $ligne->getLigneAvoirId())
                ->set('ligneProduitId', $ligne->getLigneProduitId())
                ->set('ligneUniteId', $ligne->getLigneUniteId())
                ->set('ligneDesignation', $ligne->getLigneDesignation())
                ->set('ligneQte', $ligne->getLigneQte())
                ->set('lignePrixUnitaire', $ligne->getLignePrixUnitaire())
                ->set('ligneTauxTVA', $ligne->getLigneTauxTVA())
                ->set('ligneRemise', $ligne->getLigneRemise())
                ->set('ligneTotalHT', $ligne->getLigneTotalHT())
                ->set('lignePrixNet', $ligne->getLignePrixNet())
                ->where('ligneId', $ligne->getLigneId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    public function liste($where = array(), $type = 'object') {
        $query = $this->db->select('l.*')
                ->from($this->table . ' l')
                ->join('avoirs f', 'f.avoirId = j.ligneAvoirId', 'left')
                ->where('f.avoirPdvId', $this->session->userdata('loggedPdvId'))
                ->where($where)
                ->order_by('l.ligneDesignation', 'ASC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getLignesByAvoirId(Avoir $avoir, $type = 'object') {
        $query = $this->db->select('l.*')
                ->from($this->table . ' l')
                ->join('avoirs f', 'f.avoirId = l.ligneAvoirId', 'left')
                ->where('f.avoirPdvId', $this->session->userdata('loggedPdvId'))
                ->where('l.ligneAvoirId', $avoir->getAvoirId())
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getLigneById($ligneId, $type = 'object') {
        $query = $this->db->select('l.*')
                ->from($this->table . ' l')
                ->join('avoirs f', 'f.avoirId = l.ligneAvoirId', 'left')
                ->where('f.avoirPdvId', $this->session->userdata('loggedPdvId'))
                ->where('l.ligneId', intval($ligneId))
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
