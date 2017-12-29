<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_facturetva extends My_model {

    protected $table = 'facturetva';

    const classe = 'Facturetva';

    /**
     * Ajout d'un objet de la classe Article à la BDD
     * @param Article $article Objet de la classe Article
     */
    public function ajouter(Facturetva $tva) {
        $this->db
                ->set('tvaFactureId', $tva->getTvaFactureId())
                ->set('tvaTaux', $tva->getTvaTaux())
                ->set('tvaMontant', $tva->getTvaMontant())
                ->insert($this->table);
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Article
     * @param Article $article Objet de la classe Article
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Facturetva $tva) {
        $this->db
                ->set('tvaMontant', $tva->getTvaMontant())
                ->where(array('tvaFactureId' => $tva->getTvaFactureId(), 'tvaTaux' => $tva->getTvaTaux()))
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe article
     *
     * @param Article Objet de la classe Article
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Facturetva $tva) {
        $this->db->where(array('tvaFactureId' => $tva->getTvaFactureId(), 'tvaTaux' => $tva->getTvaTaux()))
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Supprime tous les articles d'un facture
     * @param int $factureId ID du facture à réinitialiser
     * @return int Nombre d'articles supprimés
     */
    public function deleteTvaByFactureId($factureId) {
        $this->db->where('tvaFactureId', $factureId)
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function getTvaByFactureId($factureId, $type = 'object') {
        $query = $this->db->select('t.*')
                ->from($this->table . ' t')
                ->join('factures f', 'f.factureId = t.tvaFactureId', 'left')
                ->where('f.facturePdvId', $this->session->userdata('loggedPdvId'))
                ->where('tvaFactureId', $factureId)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

}
