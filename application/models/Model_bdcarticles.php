<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_bdcarticles extends MY_model {

    protected $table = 'bdcarticles';

    const classe = 'Bdcarticle';

    /**
     * Ajout d'un objet de la classe Article à la BDD
     * @param Article $article Objet de la classe Article
     */
    public function ajouter(Bdcarticle $article) {
        $this->db
                ->set('articleBdcId', $article->getArticleBdcId())
                ->set('articleProduitId', $article->getArticleProduitId())
                ->set('articleDesignation', $article->getArticleDesignation())
                ->set('articleQte', $article->getArticleQte())
                ->set('articlePrixUnitaire', $article->getArticlePrixUnitaire())
                ->set('articlePrixNet', $article->getArticlePrixNet())
                ->set('articleTauxTVA', $article->getArticleTauxTVA())
                ->set('articleRemise', $article->getArticleRemise())
                ->set('articleAction', $article->getArticleAction())
                ->set('articleApproId', $article->getArticleApproId())
                ->set('articleUniteId', $article->getArticleUniteId())
                ->set('articleQteLivree', $article->getArticleQteLivree())
                ->set('articleTotalHT', $article->getArticleTotalHT())
                ->set('articleDelete', $article->getArticleDelete())
                ->insert($this->table);
        $article->setArticleId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Article
     * @param Article $article Objet de la classe Article
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Bdcarticle $article) {
        $this->db
                ->set('articleBdcId', $article->getArticleBdcId())
                ->set('articleProduitId', $article->getArticleProduitId())
                ->set('articleDesignation', $article->getArticleDesignation())
                ->set('articleQte', $article->getArticleQte())
                ->set('articlePrixUnitaire', $article->getArticlePrixUnitaire())
                ->set('articlePrixNet', $article->getArticlePrixNet())
                ->set('articleTauxTVA', $article->getArticleTauxTVA())
                ->set('articleRemise', $article->getArticleRemise())
                ->set('articleAction', $article->getArticleAction())
                ->set('articleApproId', $article->getArticleApproId())
                ->set('articleUniteId', $article->getArticleUniteId())
                ->set('articleQteLivree', $article->getArticleQteLivree())
                ->set('articleTotalHT', $article->getArticleTotalHT())
                ->set('articleDelete', $article->getArticleDelete())
                ->where('articleId', $article->getArticleId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe article
     *
     * @param Article Objet de la classe Article
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Bdcarticle $article) {
        $this->db->where('articleId', $article->getArticleId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Supprime tous les articles d'un bdc
     * @param int $bdcId ID du bdc à réinitialiser
     * @return int Nombre d'articles supprimés
     */
    public function deleteArticleByBdcId($bdcId) {
        $this->db->where('articleBdcId', $bdcId)
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function liste($where = array(), $type = 'object') {
        $query = $this->db->select('a.*')
                ->from($this->table . ' a')
                ->join('bdc b', 'b.bdcId = a.articleBdcId', 'left')
                ->where('b.bdcPdvId', $this->session->userdata('loggedPdvId'))
                ->where('a.articleDelete', 0)
                ->where($where)
                ->order_by('a.articleDesignation', 'ASC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getArticleById($articleId, $type = 'object') {
        $query = $this->db->select('a.*')
                ->from($this->table . ' a')
                ->join('bdc b', 'b.bdcId = a.articleBdcId', 'left')
                ->where('b.bdcPdvId', $this->session->userdata('loggedPdvId'))
                ->where('a.articleId', $articleId)
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

    public function getArticlesByBdcId($bdcId, $type = 'object') {
        $query = $this->db->select('a.*')
                ->from($this->table . ' a')
                ->join('bdc b', 'b.bdcId = a.articleBdcId', 'left')
                ->where('b.bdcPdvId', $this->session->userdata('loggedPdvId'))
                ->where('a.articleBdcId', $bdcId)
                ->order_by('a.articleId')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    public function getArticlesByProduitId($produitId, $type = 'object') {
        $query = $this->db->select('a.*')
                ->from($this->table . ' a')
                ->join('bdc b', 'b.bdcId = a.articleBdcId', 'left')
                ->where('b.bdcPdvId', $this->session->userdata('loggedPdvId'))
                ->where('a.articleProduitId', intval($produitId))
                ->order_by('bdcDate DESC')
                ->get();
        return $this->retourne($query, $type, self::classe);
    }

    function encoursByProduitId($produitId) { /* retourne la somme des qte commandée - qte livrée pour un produit dont les bdc ne sont pas integralement livrés */
        $query = $this->db->select('(SUM(a.articleQte)-SUM(a.articleQteLivree)) AS encoursTotal')
                ->from($this->table . ' a')
                ->join('bdc b', 'b.bdcId = a.articleBdcId', 'left')
                //->where('b.bdcPdvId', $this->session->userdata('loggedPdvId'))
                ->where('b.bdcEtat <', 2)
                ->where(array('a.articleProduitId' => intval($produitId), '(a.articleQte - a.articleQteLivree) >' => 0))
                ->group_by('a.articleProduitId')
                ->get();
        if ($query->num_rows() > 0):
            foreach ($query->result() as $item):
                return $item->encoursTotal;
                break;
            endforeach;
        endif;
    }

}
