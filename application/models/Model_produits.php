<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_produits extends MY_Model {

    protected $table = 'produits';

    const classe = 'Produit';

    /**
     * Ajout d'un objet de la classe Produit à la BDD
     * @param Produit $produit Objet de la classe Produit
     */
    public function ajouter(Produit $produit) {
        $this->db
                ->set('produitPdvId', $produit->getProduitPdvId())
                ->set('produitEAN', $produit->getProduitEAN())
                ->set('produitRefUsine', $produit->getProduitRefUsine())
                ->set('produitUsineId', $produit->getProduitUsineId())
                ->set('produitFamilleId', $produit->getProduitFamilleId())
                ->set('produitUniteId', $produit->getProduitUniteId())
                ->set('produitDesignation', $produit->getProduitDesignation())
                ->set('produitMultiple', $produit->getProduitMultiple())
                ->set('produitPrixAchatUnitaire', $produit->getProduitPrixAchatUnitaire())
                ->set('produitPrixAchatPalette', $produit->getProduitPrixAchatPalette())
                ->set('produitSeuilPalette', $produit->getProduitSeuilPalette())
                ->set('produitPrixVente', $produit->getProduitPrixVente())
                ->set('produitPoids', $produit->getProduitPoids())
                ->set('produitGestionStock', $produit->getProduitGestionStock())
                ->set('produitGestionBain', $produit->getProduitGestionBain())
                ->set('produitTVA', $produit->getProduitTVA())
                ->set('produitArchive', $produit->getProduitArchive())
                ->insert($this->table);
        $produit->setProduitId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Produit
     * @param Produit $produit Objet de la classe Produit
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Produit $produit) {
        $this->db
                ->set('produitPdvId', $produit->getProduitPdvId())
                ->set('produitEAN', $produit->getProduitEAN())
                ->set('produitRefUsine', $produit->getProduitRefUsine())
                ->set('produitUsineId', $produit->getProduitUsineId())
                ->set('produitFamilleId', $produit->getProduitFamilleId())
                ->set('produitUniteId', $produit->getProduitUniteId())
                ->set('produitDesignation', $produit->getProduitDesignation())
                ->set('produitMultiple', $produit->getProduitMultiple())
                ->set('produitPrixAchatUnitaire', $produit->getProduitPrixAchatUnitaire())
                ->set('produitPrixAchatPalette', $produit->getProduitPrixAchatPalette())
                ->set('produitSeuilPalette', $produit->getProduitSeuilPalette())
                ->set('produitPrixVente', $produit->getProduitPrixVente())
                ->set('produitPoids', $produit->getProduitPoids())
                ->set('produitGestionStock', $produit->getProduitGestionStock())
                ->set('produitGestionBain', $produit->getProduitGestionBain())
                ->set('produitTVA', $produit->getProduitTVA())
                ->set('produitArchive', $produit->getProduitArchive())
                ->where('produitId', $produit->getProduitId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe produit
     *
     * @param Produit Objet de la classe Produit
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Produit $produit) {
        $this->db->where('produitId', $produit->getProduitId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function liste($where = array(), $tri = 'c.produitNom ASC', $type = 'object') {
        $query = $this->db->select('p.*, f.familleNom AS famille, u.usineNom AS usine, ')
                ->from($this->table . ' p')
                ->join('familles f', 'f.familleId = p.produitFamilleId', 'left')
                ->join('usines u', 'u.usineId = p.produitUsineId', 'left')
                ->where('p.produitPdvId', $this->session->userdata('loggedPdvId'))
                ->where('p.produitArchive', 0)
                ->where($where)
                ->order_by('p.produitDesignation', 'ASC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function listeAll($where = array(), $tri = 'c.produitNom ASC', $type = 'array') {
        $query = $this->db->select('p.*, f.familleNom AS famille, u.usineNom AS usine, SUM(s.stockQte) as produitStock')
                ->from($this->table . ' p')
                ->join('familles f', 'f.familleId = p.produitFamilleId', 'left')
                ->join('usines u', 'u.usineId = p.produitUsineId', 'left')
                ->join('stocks s', 's.stockProduitId = p.produitId', 'left')
                ->where('p.produitPdvId', $this->session->userdata('loggedPdvId'))
                ->where('p.produitArchive', 0)
                ->where($where)
                ->group_by('p.produitId')
                ->order_by('p.produitDesignation', 'ASC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getProduitById($produitId, $type = 'object') {
        $query = $this->db->select('p.*')->from($this->table . ' p')
                ->where('p.produitPdvId', $this->session->userdata('loggedPdvId'))
                ->where('p.produitArchive', 0)
                ->where('p.produitId', $produitId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
