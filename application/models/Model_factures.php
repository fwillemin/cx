<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_factures extends MY_model {

    protected $table = 'factures';

    const classe = 'Facture';

    /**
     * Ajout d'un objet de la classe Facture à la BDD
     * @param Facture $facture Objet de la classe Facture
     */
    public function ajouter(Facture $facture) {
        $this->db
                ->set('facturePdvId', $facture->getFacturePdvId())
                ->set('factureDate', $facture->getFactureDate())
                ->set('factureClientId', $facture->getFactureClientId())
                ->set('factureTotalHT', $facture->getFactureTotalHT())
                ->set('factureTotalTVA', $facture->getFactureTotalTVA())
                ->set('factureTotalTTC', $facture->getFactureTotalTTC())
                ->set('factureConditionsReglementId', $facture->getFactureConditionsReglementId())
                ->set('factureSolde', $facture->getFactureSolde())
                ->set('factureEcheance', $facture->getFactureEcheance())
                ->insert($this->table);
        $facture->setFactureId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Facture
     * @param Facture $facture Objet de la classe Facture
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Facture $facture) {
        $this->db
                ->set('facturePdvId', $facture->getFacturePdvId())
                ->set('factureDate', $facture->getFactureDate())
                ->set('factureClientId', $facture->getFactureClientId())
                ->set('factureTotalHT', $facture->getFactureTotalHT())
                ->set('factureTotalTVA', $facture->getFactureTotalTVA())
                ->set('factureTotalTTC', $facture->getFactureTotalTTC())
                ->set('factureConditionsReglementId', $facture->getFactureConditionsReglementId())
                ->set('factureSolde', $facture->getFactureSolde())
                ->set('factureEcheance', $facture->getFactureEcheance())
                ->where('factureId', $facture->getFactureId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    public function liste($where = array(), $type = 'object') {
        $query = $this->db->select('f.*')
                ->from($this->table . ' f')
                ->join('facturelignes l', 'l.ligneFactureId = f.factureId')
                ->where('f.facturePdvId', $this->session->userdata('loggedPdvId'))
                ->where($where)
                ->group_by('f.factureId')
                ->order_by('f.factureDate DESC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function delete(Facture $facture) {
        $this->db->where('factureId', $facture->getFactureId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function getFactureById($factureId, $type = 'object') {
        $query = $this->db->select('f.*')
                ->from($this->table . ' f')
                ->where('f.facturePdvId', $this->session->userdata('loggedPdvId'))
                ->where('f.factureId', $factureId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    /**
     *  retourne une liste de factures non supprimées pour un BDC
     */
    function getFacturesByBdcId($bdcId, $type = 'object') {
        $query = $this->db->select('f.*')
                ->from($this->table . ' f')
                ->join('bl bl', 'bl.blFactureId = f.factureId')
                ->join('bdc b', 'b.bdcId = bl.blBdcId')
                ->where('f.facturePdvId', $this->session->userdata('loggedPdvId'))
                ->where('b.bdcId', $bdcId)
                ->group_by('f.factureId')
                ->order_by('f.factureDate DESC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

}
