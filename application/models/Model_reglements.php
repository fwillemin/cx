<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_reglements extends MY_model {

    protected $table = 'reglements';

    const classe = 'Reglement';

    /**
     * Ajout d'un objet de la classe Article à la BDD
     * @param Article $reglement Objet de la classe Article
     */
    public function ajouter(Reglement $reglement) {
        $this->db
                ->set('reglementBdcId', $reglement->getReglementBdcId())
                ->set('reglementFactureId', $reglement->getReglementFactureId())
                ->set('reglementClientId', $reglement->getReglementClientId())
                ->set('reglementModeId', $reglement->getReglementModeId())
                ->set('reglementRemiseId', $reglement->getReglementRemiseId())
                ->set('reglementMontant', $reglement->getReglementMontant())
                ->set('reglementDate', $reglement->getReglementDate())
                ->set('reglementType', $reglement->getReglementType())
                ->set('reglementSourceId', $reglement->getReglementSourceId())
                ->set('reglementGroupeId', $reglement->getReglementGroupeId())
                ->set('reglementUtile', $reglement->getReglementUtile())
                ->set('reglementToken', $reglement->getReglementToken())
                ->insert($this->table);
        $reglement->setReglementId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Article
     * @param Article $reglement Objet de la classe Article
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Reglement $reglement) {
        $this->db
                ->set('reglementBdcId', $reglement->getReglementBdcId())
                ->set('reglementFactureId', $reglement->getReglementFactureId())
                ->set('reglementClientId', $reglement->getReglementClientId())
                ->set('reglementModeId', $reglement->getReglementModeId())
                ->set('reglementRemiseId', $reglement->getReglementRemiseId())
                ->set('reglementMontant', $reglement->getReglementMontant())
                ->set('reglementDate', $reglement->getReglementDate())
                ->set('reglementType', $reglement->getReglementType())
                ->set('reglementSourceId', $reglement->getReglementSourceId())
                ->set('reglementGroupeId', $reglement->getReglementGroupeId())
                ->set('reglementUtile', $reglement->getReglementUtile())
                ->set('reglementToken', $reglement->getReglementToken())
                ->where('reglementId', $reglement->getReglementId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    public function delete(Reglement $reglement) {
        $this->db->where('reglementId', $reglement->getReglementId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function liste($where = array(), $tri = 'reglementDate DESC', $type = 'object') {
        $query = $this->db->select('r.*, c.clientNom AS client')
                ->from($this->table . ' r') /* probleme ici en cas de multicompte */
                ->join('clients c', 'c.clientId = r.reglementClientId')
                ->where($where)
                ->order_by($tri)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getReglementsByBdcId($bdcId, $type = 'object') {
        $query = $this->db->select('r.*')->from($this->table . ' r')
                ->where(array('r.reglementBdcId' => $bdcId, 'reglementUtile' => 1))
                ->order_by('r.reglementDate', 'DESC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Retourne un array avec d'historique d'un réglement à savoir tous les réglements l'ayant pour source
     * @param integer $reglementSourceId ID du réglement source
     * @return array Liste d'objets de la classe Reglement
     */
    public function historique($reglementGroupeId, $type = 'object') {
        $query = $this->db->select('r.*')
                ->from('reglements r')
                ->where(array('reglementGroupeId' => $reglementGroupeId, 'reglementUtile' => 0))
                ->order_by('reglementDate DESC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    /**
     * Liste des réglements pour une facture
     * @param Facture $facture Facture
     * @param string $type Format du retour des réglements (object/array)
     * @return array Liste des réglements
     */
    public function getReglementsByFactureId(Facture $facture, $type = 'object') {
        $query = $this->db->select('r.*')->from($this->table . ' r')
                ->where(array('r.reglementFactureId' => $facture->getFactureId(), 'reglementUtile' => 1))
                ->order_by('r.reglementDate', 'DESC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getReglementsByGroupeId($groupeId, $type = 'object') {
        $query = $this->db->select('r.*')->from($this->table . ' r')
                ->where(array('r.reglementGroupeId' => $groupeId, 'reglementUtile' => 1))
                ->order_by('r.reglementDate', 'DESC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getReglementsByRemiseId($remiseId, $type = 'object') {
        $query = $this->db->select('r.*')->from($this->table . ' r')
                ->where(array('r.reglementRemiseId' => intval($remiseId)))
                ->order_by('r.reglementDate', 'DESC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getChequesPeriode($debut, $fin, $type = 'object') {
        $query = $this->db->select('r.*')
                ->from($this->table . ' r')
                ->where(array('r.reglementModeId' => 2, 'reglementDate >=' => $debut, 'reglementDate <=' => $fin))
                ->order_by('r.reglementDate', 'DESC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getReglementById($reglementId, $type = 'object') {
        $query = $this->db->select('r.*')
                ->from($this->table . ' r')
                ->where('r.reglementId', $reglementId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    public function reglementsClient($clientId) { /* retourne tous les reglements pour un client */
        return $this->db->select('r.*')->from($this->table . ' r')
                        ->where(array('r.reglementClientId' => $clientId))
                        ->order_by('r.reglementDate', 'DESC')
                        ->get()
                        ->result();
    }

    public function remiseCheque($start, $end) { /* liste les cheques de la periode avec le client, la facture et la remise de chèque */
        return $this->db->select('r.*, c.clientNom AS client,rc.*')->from($this->table . ' r') /* probleme ici en cas de multicompte */
                        ->join('clients c', 'c.clientId = r.reglementClientId')
                        ->join('remisecheques rc', 'rc.remiseId = r.reglementRemiseId', 'left')
                        ->where('c.clientPdvId', $this->session->userdata('loggedPdvId'))
                        ->where(array('r.reglementMode' => 2, 'reglementDate >=' => $start, 'reglementDate <=' => $end))
                        ->order_by('r.reglementId', 'ASC')
                        ->get()
                        ->result();
    }

    public function chequesRemise($remiseId) { /* -- liste des cheques inclus dans la remise */
        return $this->db->select('r.*, c.clientNom AS client,rc.*')->from($this->table . ' r')
                        ->join('clients c', 'c.clientId = r.reglementClientId')
                        ->join('remisecheques rc', 'rc.remiseId = r.reglementRemiseId', 'left')
                        ->where('c.clientPdvId', $this->session->userdata('loggedPdvId'))
                        ->where(array('r.reglementMode' => 2, 'r.reglementRemiseId' => $remiseId))
                        ->order_by('r.reglementId', 'ASC')
                        ->get()
                        ->result();
    }

    public function chequesRemisesArray($remises) { /* liste des cheques contenus dans les remises dont les ids sont dans l'array $remises */
        return $this->db->select('r.*, c.clientNom AS client')->from($this->table . ' r')
                        ->join('clients c', 'c.clientId = r.reglementClientId')
                        ->where_in('reglementRemiseId', $remises)
                        ->order_by('r.reglementId', 'ASC')
                        ->get()
                        ->result();
    }

}
