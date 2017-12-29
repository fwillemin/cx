<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_devisarticles extends MY_model {

    protected $table = 'devisarticles';

    const classe = 'devisarticle';

    public function ajouter(Devisarticle $article) {
        $this->db
                ->set('articleDevisId', $article->getArticleDevisId())
                ->set('articleProduitId', $article->getArticleProduitId())
                ->set('articleDesignation', $article->getArticleDesignation())
                ->set('articleQte', $article->getArticleQte())
                ->set('articlePrixUnitaire', $article->getArticlePrixUnitaire())
                ->set('articleTauxTVA', $article->getArticleTauxTVA())
                ->set('articleRemise', $article->getArticleRemise())
                ->set('articleUniteId', $article->getArticleUniteId())
                ->set('articlePrixNet', $article->getArticlePrixNet())
                ->set('articleTotalHT', $article->getArticleTotalHT())
                ->insert($this->table);
        $article->setArticleId($this->db->insert_id());
    }

    public function editer(Devisarticle $article) {
        $this->db
                ->set('articleDevisId', $article->getArticleDevisId())
                ->set('articleProduitId', $article->getArticleProduitId())
                ->set('articleDesignation', $article->getArticleDesignation())
                ->set('articleQte', $article->getArticleQte())
                ->set('articlePrixUnitaire', $article->getArticlePrixUnitaire())
                ->set('articleTauxTVA', $article->getArticleTauxTVA())
                ->set('articleRemise', $article->getArticleRemise())
                ->set('articleUniteId', $article->getArticleUniteId())
                ->set('articlePrixNet', $article->getArticlePrixNet())
                ->set('articleTotalHT', $article->getArticleTotalHT())
                ->where('articleId', $article->getArticleId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    public function delete(Devisarticle $article) {
        $this->db->where('articleId', $article->getArticleId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Supprime tous les articles d'un devis
     * @param int $devisId ID du devis à réinitialiser
     * @return int Nombre d'articles supprimés
     */
    public function deleteArticlesByDevisId($devisId) {
        $this->db->where('articleDevisId', $devisId)
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function liste($where = array(), $type = 'object') {
        $query = $this->db->select('a.*')
                ->from($this->table . ' a')
                ->join('devis d', 'd.devisId = a.articleDevisId', 'left')
                ->where('d.devisPdvId', $this->session->userdata('loggedPdvId'))
                ->where($where)
                ->order_by('a.articleDesignation', 'ASC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getArticlesByDevisId($devisId, $type = 'object') {
        $query = $this->db->select('a.*')
                ->from($this->table . ' a')
                ->join('devis d', 'd.devisId = a.articleDevisId', 'left')
                ->where('d.devisPdvId', $this->session->userdata('loggedPdvId'))
                ->where('d.devisId', intval($devisId))
                ->order_by('a.articleId', 'ASC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getArticleById($articleId, $type = 'object') {
        $query = $this->db->select('a.*')
                ->from($this->table . ' a')
                ->join('devis d', 'd.devisId = a.articleDevisId', 'left')
                ->where('d.devisPdvId', $this->session->userdata('loggedPdvId'))
                ->where('a.articleId', $articleId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
