<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_avoirtva extends My_model {

    protected $table = 'avoirtva';

    const classe = 'Avoirtva';

    /**
     * Ajout d'un objet de la classe Article à la BDD
     * @param Article $article Objet de la classe Article
     */
    public function ajouter(Avoirtva $tva) {
        $this->db
                ->set('tvaAvoirId', $tva->getTvaAvoirId())
                ->set('tvaTaux', $tva->getTvaTaux())
                ->set('tvaMontant', $tva->getTvaMontant())
                ->insert($this->table);
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Article
     * @param Article $article Objet de la classe Article
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Avoirtva $tva) {
        $this->db
                ->set('tvaMontant', $tva->getTvaMontant())
                ->where(array('tvaAvoirId' => $tva->getTvaAvoirId(), 'tvaTaux' => $tva->getTvaTaux()))
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe article
     *
     * @param Article Objet de la classe Article
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Avoirtva $tva) {
        $this->db->where(array('tvaAvoirId' => $tva->getTvaAvoirId(), 'tvaTaux' => $tva->getTvaTaux()))
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Supprime tous les articles d'un avoir
     * @param int $avoirId ID du avoir à réinitialiser
     * @return int Nombre d'articles supprimés
     */
    public function deleteTvaByAvoirId($avoirId) {
        $this->db->where('tvaAvoirId', $avoirId)
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function getTvaByAvoirId($avoirId, $type = 'object') {
        $query = $this->db->select('t.*')
                ->from($this->table . ' t')
                ->join('avoirs f', 'f.avoirId = t.tvaAvoirId', 'left')
                ->where('f.avoirPdvId', $this->session->userdata('loggedPdvId'))
                ->where('tvaAvoirId', $avoirId)
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

}
