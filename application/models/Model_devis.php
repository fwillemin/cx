<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_devis extends MY_model {

    protected $table = 'devis';

    const classe = 'Devis';

    /**
     * Ajout d'un objet de la classe Devis à la BDD
     * @param Devis $devis Objet de la classe Devis
     */
    public function ajouter(Devis $devis) {
        $this->db
                ->set('devisPdvId', $devis->getDevisPdvId())
                ->set('devisEtat', $devis->getDevisEtat())
                ->set('devisCollaborateurId', $devis->getDevisCollaborateurId())
                ->set('devisDateCreation', $devis->getDevisDateCreation())
                ->set('devisDate', $devis->getDevisDate())
                ->set('devisClientId', $devis->getDevisClientId())
                ->set('devisNbArticles', $devis->getDevisNbArticles())
                ->set('devisTotalHT', $devis->getDevisTotalHT())
                ->set('devisTotalTVA', $devis->getDevisTotalTVA())
                ->set('devisTotalTTC', $devis->getDevisTotalTTC())
                ->set('devisPoids', $devis->getDevisPoids())
                ->set('devisDelete', $devis->getDevisDelete())
                ->insert($this->table);
        $devis->setDevisId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Devis
     * @param Devis $devis Objet de la classe Devis
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Devis $devis) {
        $this->db
                ->set('devisPdvId', $devis->getDevisPdvId())
                ->set('devisEtat', $devis->getDevisEtat())
                ->set('devisCollaborateurId', $devis->getDevisCollaborateurId())
                ->set('devisDateCreation', $devis->getDevisDateCreation())
                ->set('devisDate', $devis->getDevisDate())
                ->set('devisClientId', $devis->getDevisClientId())
                ->set('devisNbArticles', $devis->getDevisNbArticles())
                ->set('devisTotalHT', $devis->getDevisTotalHT())
                ->set('devisTotalTVA', $devis->getDevisTotalTVA())
                ->set('devisTotalTTC', $devis->getDevisTotalTTC())
                ->set('devisPoids', $devis->getDevisPoids())
                ->set('devisDelete', $devis->getDevisDelete())
                ->where('devisId', $devis->getDevisId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe devis
     *
     * @param Devis Objet de la classe Devis
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Devis $devis) {
        $this->db->where('devisId', $devis->getDevisId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function liste($where = array(), $type = 'object') {
        $query = $this->db->select('*')
                ->from('devis')
                ->where('devisPdvId', $this->session->userdata('loggedPdvId'))
                ->where($where)
                ->order_by('devisDate DESC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function listeNonConvertis($where = array(), $type = 'object') {
        $query = $this->db->select('d.*, b.bdcId AS bdc, CONCAT_WS( " ", c.clientNom, c.clientPrenom ) AS devisClient, c.clientRaisonSociale AS devisRaisonSociale, c.clientVille AS devisVille')
                ->from($this->table . ' d')
                ->join('bdc b', 'b.bdcDevisId = d.devisId', 'left')
                ->join('clients c', 'c.clientId = d.devisClientId', 'left')
                ->where(array('d.devisPdvId' => $this->session->userdata('loggedPdvId'), 'b.bdcDevisId' => null, 'd.devisEtat' => 0, 'd.devisDelete' => 0))
                ->where($where)
                ->order_by('d.devisDate', 'DESC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getDevisById($devisId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where('devisPdvId', $this->session->userdata('loggedPdvId'))
                ->where('devisId', $devisId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
