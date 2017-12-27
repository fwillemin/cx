<?php

/**
 * Classe de gestion des Bdcs.
 * Manager : Model_bdcs
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Bdcarticle {

    protected $articleId;
    protected $articleBdcId;
    protected $articleBdc;
    protected $articleProduitId;
    protected $articleProduit;
    protected $articleDesignation;
    protected $articleQte;
    protected $articlePrixUnitaire;
    protected $articlePrixNet;
    protected $articleTauxTVA;
    protected $articleRemise;
    protected $articleApproId;
    protected $articleAction;
    protected $articleAppro;
    protected $articleCommandeAppro;
    protected $articleUniteId;
    protected $articleQteLivree;
    protected $articleTotalHT;
    protected $articleTotalTVA;
    protected $articleTotalTTC;
    protected $articleDelete;

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
//        $this->hydrateApproInfo();
//        $this->hydrateCommandeApproInfo();
    }

    function hydrateApproInfo() {
        $CI = & get_instance();
        if ($this->getArticleApproId()):
            $this->setArticleAppro($CI->managerAppros->getApproById($this->getArticleApproId()));
        endif;
    }

    function hydrateCommandeApproInfo() {
        $CI = & get_instance();
        if ($this->getArticleApproId()):
            $this->setArticleCommandeAppro($CI->managerCommandes->getCommandeById($this->getArticleApproId()));
        endif;
    }

    function hydrateBdc() {
        $CI = & get_instance();
        $this->setArticleBdc($CI->managerBdc->getBdcById($this->getArticleBdcId()));
    }

    function getBdcId() {
        return $this->bdcId;
    }

    function getBdcPdvId() {
        return $this->bdcPdvId;
    }

    function getBdcUserId() {
        return $this->bdcUserId;
    }

    function getBdcDevisId() {
        return $this->bdcDevisId;
    }

    function getBdcDateCreation() {
        return $this->bdcDateCreation;
    }

    function getBdcDate() {
        return $this->bdcDate;
    }

    function getBdcClientId() {
        return $this->bdcClientId;
    }

    function getBdcNbArticles() {
        return $this->bdcNbArticles;
    }

    function getBdcTotalHT() {
        return $this->bdcTotalHT;
    }

    function getBdcTotalTVA() {
        return $this->bdcTotalTVA;
    }

    function getBdcTotalTTC() {
        return $this->bdcTotalTTC;
    }

    function getBdcAcompte() {
        return $this->bdcAcompte;
    }

    function getBdcPoids() {
        return $this->bdcPoids;
    }

    function getBdcEtat() {
        return $this->bdcEtat;
    }

    function getBdcCommentaire() {
        return $this->bdcCommentaire;
    }

    function setBdcId($bdcId) {
        $this->bdcId = $bdcId;
    }

    function setBdcPdvId($bdcPdvId) {
        $this->bdcPdvId = $bdcPdvId;
    }

    function setBdcUserId($bdcUserId) {
        $this->bdcUserId = $bdcUserId;
    }

    function setBdcDevisId($bdcDevisId) {
        $this->bdcDevisId = $bdcDevisId;
    }

    function setBdcDateCreation($bdcDateCreation) {
        $this->bdcDateCreation = $bdcDateCreation;
    }

    function setBdcDate($bdcDate) {
        $this->bdcDate = $bdcDate;
    }

    function setBdcClientId($bdcClientId) {
        $this->bdcClientId = $bdcClientId;
    }

    function setBdcNbArticles($bdcNbArticles) {
        $this->bdcNbArticles = $bdcNbArticles;
    }

    function setBdcTotalHT($bdcTotalHT) {
        $this->bdcTotalHT = $bdcTotalHT;
    }

    function setBdcTotalTVA($bdcTotalTVA) {
        $this->bdcTotalTVA = $bdcTotalTVA;
    }

    function setBdcTotalTTC($bdcTotalTTC) {
        $this->bdcTotalTTC = $bdcTotalTTC;
    }

    function setBdcAcompte($bdcAcompte) {
        $this->bdcAcompte = $bdcAcompte;
    }

    function setBdcPoids($bdcPoids) {
        $this->bdcPoids = $bdcPoids;
    }

    function setBdcEtat($bdcEtat) {
        $this->bdcEtat = $bdcEtat;
    }

    function setBdcCommentaire($bdcCommentaire) {
        $this->bdcCommentaire = $bdcCommentaire;
    }

    function getArticleId() {
        return $this->articleId;
    }

    function getArticleBdcId() {
        return $this->articleBdcId;
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

    function getArticleApproId() {
        return $this->articleApproId;
    }

    function getArticleUniteId() {
        return $this->articleUniteId;
    }

    function getArticleQteLivree() {
        return $this->articleQteLivree;
    }

    function getArticleTotalHT() {
        return $this->articleTotalHT;
    }

    function getArticleTotalTVA() {
        return $this->articleTotalTVA;
    }

    function getArticleTotalTTC() {
        return $this->articleTotalTTC;
    }

    function setArticleId($articleId) {
        $this->articleId = $articleId;
    }

    function setArticleBdcId($articleBdcId) {
        $this->articleBdcId = $articleBdcId;
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

    function setArticleApproId($articleApproId) {
        $this->articleApproId = $articleApproId;
    }

    function setArticleUniteId($articleUniteId) {
        $this->articleUniteId = $articleUniteId;
    }

    function setArticleQteLivree($articleQteLivree) {
        $this->articleQteLivree = $articleQteLivree;
    }

    function setArticleTotalHT($articleTotalHT) {
        $this->articleTotalHT = $articleTotalHT;
    }

    function setArticleTotalTVA($articleTotalTVA) {
        $this->articleTotalTVA = $articleTotalTVA;
    }

    function setArticleTotalTTC($articleTotalTTC) {
        $this->articleTotalTTC = $articleTotalTTC;
    }

    function getArticleCommandeId() {
        return $this->articleCommandeId;
    }

    function setArticleCommandeId($articleCommandeId) {
        $this->articleCommandeId = $articleCommandeId;
    }

    function getArticleAppro() {
        return $this->articleAppro;
    }

    function getArticleCommandeAppro() {
        return $this->articleCommandeAppro;
    }

    function setArticleAppro($articleAppro) {
        $this->articleAppro = $articleAppro;
    }

    function setArticleCommandeAppro($articleCommandeAppro) {
        $this->articleCommandeAppro = $articleCommandeAppro;
    }

    function getArticleAction() {
        return $this->articleAction;
    }

    function setArticleAction($articleAction) {
        $this->articleAction = $articleAction;
    }

    function getArticleDelete() {
        return $this->articleDelete;
    }

    function setArticleDelete($articleDelete) {
        $this->articleDelete = $articleDelete;
    }

    function getArticleBdc() {
        return $this->articleBdc;
    }

    function setArticleBdc($articleBdc) {
        $this->articleBdc = $articleBdc;
    }

    function getArticlePrixNet() {
        return $this->articlePrixNet;
    }

    function setArticlePrixNet($articlePrixNet) {
        $this->articlePrixNet = $articlePrixNet;
    }

}
