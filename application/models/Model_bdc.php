<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_bdc extends MY_model {

    protected $table = 'bdc';

    const classe = 'Bdc';

    /**
     * Ajout d'un objet de la classe Bdc à la BDD
     * @param Bdc $bdc Objet de la classe Bdc
     */
    public function ajouter(Bdc $bdc) {
        $this->db
                ->set('bdcPdvId', $bdc->getBdcPdvId())
                ->set('bdcCollaborateurId', $bdc->getBdcCollaborateurId())
                ->set('bdcDevisId', $bdc->getBdcDevisId())
                ->set('bdcDateCreation', $bdc->getBdcDateCreation())
                ->set('bdcDate', $bdc->getBdcDate())
                ->set('bdcClientId', $bdc->getBdcClientId())
                ->set('bdcNbArticles', $bdc->getBdcNbArticles())
                ->set('bdcTotalHT', $bdc->getBdcTotalHT())
                ->set('bdcTotalTVA', $bdc->getBdcTotalTVA())
                ->set('bdcTotalTTC', $bdc->getBdcTotalTTC())
                ->set('bdcPoids', $bdc->getBdcPoids())
                ->set('bdcEtat', $bdc->getBdcEtat())
                ->set('bdcCommentaire', $bdc->getBdcCommentaire())
                ->set('bdcDelete', $bdc->getBdcDelete())
                ->insert($this->table);
        $bdc->setBdcId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Bdc
     * @param Bdc $bdc Objet de la classe Bdc
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Bdc $bdc) {
        $this->db
                ->set('bdcPdvId', $bdc->getBdcPdvId())
                ->set('bdcCollaborateurId', $bdc->getBdcCollaborateurId())
                ->set('bdcDevisId', $bdc->getBdcDevisId())
                ->set('bdcDateCreation', $bdc->getBdcDateCreation())
                ->set('bdcDate', $bdc->getBdcDate())
                ->set('bdcClientId', $bdc->getBdcClientId())
                ->set('bdcNbArticles', $bdc->getBdcNbArticles())
                ->set('bdcTotalHT', $bdc->getBdcTotalHT())
                ->set('bdcTotalTVA', $bdc->getBdcTotalTVA())
                ->set('bdcTotalTTC', $bdc->getBdcTotalTTC())
                ->set('bdcPoids', $bdc->getBdcPoids())
                ->set('bdcEtat', $bdc->getBdcEtat())
                ->set('bdcCommentaire', $bdc->getBdcCommentaire())
                ->set('bdcDelete', $bdc->getBdcDelete())
                ->where('bdcId', $bdc->getBdcId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe bdc
     *
     * @param Bdc Objet de la classe Bdc
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Bdc $bdc) {
        $this->db->where('bdcId', $bdc->getBdcId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function liste($where = array(), $type = 'object') {
        $query = $this->db->select('b.*, SUM(CASE WHEN a.articleApproId != "1000000000" THEN a.articleApproId ELSE 0 END) AS nbAppro')->from($this->table . ' b')
                ->join('bdcarticles a', 'a.articleBdcId = b.bdcId')
                ->where('b.bdcPdvId', $this->session->userdata('loggedPdvId'))
                ->where('b.bdcDelete', 0)
                ->where($where)
                ->group_by('b.bdcId')
                ->order_by('b.bdcDate DESC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function listeListing($where = array(), $type = 'array') {
        $query = $this->db->select('b.*, CONCAT_WS( " ", c.clientNom, c.clientPrenom ) AS bdcClient, c.clientVille AS bdcVille')
                ->from($this->table . ' b')
                ->join('bdcarticles a', 'a.articleBdcId = b.bdcId')
                ->join('clients c', 'c.clientId = b.bdcClientId', 'left')
                ->where('b.bdcPdvId', $this->session->userdata('loggedPdvId'))
                ->where('b.bdcDelete', 0)
                ->where($where)
                ->group_by('b.bdcId')
                ->order_by('b.bdcDate DESC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getBdcById($bdcId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('bdcPdvId', $this->session->userdata('loggedPdvId'))
                ->where('bdcId', $bdcId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    public function getBdcByDevisId($devisId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('bdcPdvId', $this->session->userdata('loggedPdvId'))
                ->where('bdcDevisId', $devisId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    /**
     *  retourne une liste de bons de commande dont les articles sont approivionnés par la commande usine commandeId
     */
    function bdcByCommandeId($commandeId, $retour = 'object') {
        $query = $this->db->select('b.*')
                ->from($this->table . ' b')
                ->join('commandearticles a', 'a.approId = ba.articleApproId', 'left')
                ->join('commandes co', 'co.commandeId = a.approCommandeId', 'left')
                ->where('b.bdcPdvId', $this->session->userdata('loggedPdvId'))
                ->where('b.bdcDelete', 0)
                ->where('co.commandeId', intval($commandeId))
                ->group_by('b.bdcId')
                ->order_by('b.bdcDate DESC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function listeFULL($where = array(), $type = 'object') {
        $query = $this->db->select('b.*')->from($this->table . ' b')
                ->join('bdcarticles a', 'a.articleBdcId = b.bdcId')
                ->where('b.bdcPdvId', $this->session->userdata('loggedPdvId'))
                ->where($where)
                ->group_by('b.bdcId')
                ->order_by('b.bdcDate DESC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

}
