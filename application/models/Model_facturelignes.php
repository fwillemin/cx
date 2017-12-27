<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_facturelignes extends MY_model {

    protected $table = 'facturelignes';

    const classe = 'Factureligne';

    /**
     * Ajout d'un objet de la classe Factureligne à la BDD
     * @param Factureligne $ligne Objet de la classe Ligne
     */
    public function ajouter(FactureLigne $ligne) {
        $this->db
                ->set('ligneFactureId', $ligne->getLigneFactureId())
                ->set('ligneBlId', $ligne->getLigneBlId())
                ->set('ligneLivraisonId', $ligne->getLigneLivraisonId())
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
    public function editer(Factureligne $ligne) {
        $this->db
                ->set('ligneFactureId', $ligne->getLigneFactureId())
                ->set('ligneBlId', $ligne->getLigneBlId())
                ->set('ligneLivraisonId', $ligne->getLigneLivraisonId())
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
                ->join('factures f', 'f.factureId = j.ligneFactureId', 'left')
                ->where('f.facturePdvId', $this->session->userdata('loggedPdvId'))
                ->where($where)
                ->order_by('l.ligneDesignation', 'ASC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getLignesByFactureId(Facture $facture, $type = 'object') {
        $query = $this->db->select('l.*')
                ->from($this->table . ' l')
                ->join('factures f', 'f.factureId = l.ligneFactureId', 'left')
                ->where('f.facturePdvId', $this->session->userdata('loggedPdvId'))
                ->where('l.ligneFactureId', $facture->getFactureId())
                ->order_by('l.ligneBlId, ligneLivraisonId ', 'ASC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getLigneById($ligneId, $type = 'object') {
        $query = $this->db->select('l.*')
                ->from($this->table . ' l')
                ->join('factures f', 'f.factureId = l.ligneFactureId', 'left')
                ->where('f.facturePdvId', $this->session->userdata('loggedPdvId'))
                ->where('l.ligneId', intval($ligneId))
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
