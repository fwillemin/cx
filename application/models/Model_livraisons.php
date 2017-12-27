<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_livraisons extends MY_model {

    protected $table = 'livraisons';

    const classe = 'Livraison';

    /**
     * Ajout d'un objet de la classe Livraison à la BDD
     * @param Livraison $livraison Objet de la classe Livraison
     */
    public function ajouter(Livraison $livraison) {
        $this->db
                ->set('livraisonBlId', $livraison->getLivraisonBlId())
                ->set('livraisonArticleId', $livraison->getLivraisonArticleId())
                ->set('livraisonStockId', $livraison->getLivraisonStockId())
                ->set('livraisonQte', $livraison->getLivraisonQte())
                ->insert($this->table);
        $livraison->setLivraisonId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Livraison
     * @param Livraison $livraison Objet de la classe Livraison
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Livraison $livraison) {
        $this->db
                ->set('livraisonBlId', $livraison->getLivraisonBlId())
                ->set('livraisonArticleId', $livraison->getLivraisonArticleId())
                ->set('livraisonStockId', $livraison->getLivraisonStockId())
                ->set('livraisonQte', $livraison->getLivraisonQte())
                ->where('livraisonId', $livraison->getLivraisonId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe livraison
     *
     * @param Livraison Objet de la classe Livraison
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Livraison $livraison) {
        $this->db->where('livraisonId', $livraison->getLivraisonId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function liste($where = array(), $type = 'object') {
        $query = $this->db->select('l.*')
                ->from($this->table . ' l')
                ->join('bl b', 'b.blId = l.livraisonBlId', 'left')
                ->where('b.blPdvId', $this->session->userdata('loggedPdvId'))
                ->where($where)
                ->order_by('l.livraisonId', 'ASC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getLivraisonById($livraisonId, $type = 'object') {
        $query = $this->db->select('l.*')
                ->from($this->table . ' l')
                ->join('bl b', 'b.blId = l.livraisonBlId', 'left')
                ->where('b.blPdvId', $this->session->userdata('loggedPdvId'))
                ->where('l.livraisonId', intval($livraisonId))
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    public function getLivraisonByBlId($blId, $type = 'object') {
        $query = $this->db->select('l.*')
                ->from($this->table . ' l')
                ->join('bl b', 'b.blId = l.livraisonBlId', 'left')
                ->where('b.blPdvId', $this->session->userdata('loggedPdvId'))
                ->where('l.livraisonBlId', intval($blId))
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

}
