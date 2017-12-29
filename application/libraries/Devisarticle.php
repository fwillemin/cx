<?php

/**
 * Classe de gestion des Deviss.
 * Manager : Model_deviss
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Devisarticle {

    protected $articleId;
    protected $articleDevisId;
    protected $articleProduitId;
    protected $articleProduit;
    protected $articleDesignation;
    protected $articleQte;
    protected $articlePrixUnitaire;
    protected $articleTauxTVA;
    protected $articleRemise;
    protected $articlePrixNet; /* HT */
    protected $articleTotalHT;
    protected $articleUniteId;

    public function __construct(array $valeurs = []) {
        /* Si on passe des valeurs, on hydrate l'objet */
        if (!empty($valeurs))
            $this->hydrate($valeurs);
    }

    public function hydrate(array $donnees) {
        foreach ($donnees as $key => $value):
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method))
                $this->$method($value);
        endforeach;

        $CI = & get_instance();
        $this->setArticleProduit($CI->managerProduits->getProduitById($this->getArticleProduitId()));
    }

    function getArticleId() {
        return $this->articleId;
    }

    function getArticleDevisId() {
        return $this->articleDevisId;
    }

    function getArticleProduitId() {
        return $this->articleProduitId;
    }

    function getArticleProduit() {
        return $this->articleProduit;
    }

    function getArticleDesignation() {
        return $this->articleDesignation;
    }

    function getArticleQte() {
        return $this->articleQte;
    }

    function getArticlePrixUnitaire() {
        return $this->articlePrixUnitaire;
    }

    function getArticleTauxTVA() {
        return $this->articleTauxTVA;
    }

    function getArticleRemise() {
        return $this->articleRemise;
    }

    function getArticlePrixNet() {
        return $this->articlePrixNet;
    }

    function getArticleUniteId() {
        return $this->articleUniteId;
    }

    function setArticleId($articleId) {
        $this->articleId = $articleId;
    }

    function setArticleDevisId($articleDevisId) {
        $this->articleDevisId = $articleDevisId;
    }

    function setArticleProduitId($articleProduitId) {
        $this->articleProduitId = $articleProduitId;
    }

    function setArticleProduit($articleProduit) {
        $this->articleProduit = $articleProduit;
    }

    function setArticleDesignation($articleDesignation) {
        $this->articleDesignation = $articleDesignation;
    }

    function setArticleQte($articleQte) {
        $this->articleQte = $articleQte;
    }

    function setArticlePrixUnitaire($articlePrixUnitaire) {
        $this->articlePrixUnitaire = $articlePrixUnitaire;
    }

    function setArticleTauxTVA($articleTauxTVA) {
        $this->articleTauxTVA = $articleTauxTVA;
    }

    function setArticleRemise($articleRemise) {
        $this->articleRemise = $articleRemise;
    }

    function setArticlePrixNet($articlePrixNet) {
        $this->articlePrixNet = $articlePrixNet;
    }

    function setArticleUniteId($articleUniteId) {
        $this->articleUniteId = $articleUniteId;
    }

    function getArticleTotalHT() {
        return $this->articleTotalHT;
    }

    function setArticleTotalHT($articleTotalHT) {
        $this->articleTotalHT = $articleTotalHT;
    }

}
