<?php

/**
 * Classe de gestion des CommandeArticles.
 * Manager : Model_bdcs
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Reglement {

    protected $reglementId;
    protected $reglementSourceId; /* Id du réglement source pour la tracablité */
    protected $reglementGroupeId; /* Id du réglement d'origine, premier de la chaine pour la tracablité */
    protected $reglementUtile; /* Id du réglement source pour la tracablité */
    protected $reglementToken;
    protected $reglementSecure; /* Bool */
    protected $reglementHistorique; /* Historique des modifications de ce réglement -> réglements avec le même SourceId */
    protected $reglementBdcId;
    protected $reglementFactureId;
    protected $reglementClientId;
    protected $reglementClient;
    protected $reglementModeId;
    protected $reglementMode;
    protected $reglementRemiseId; /* Remise de chèque en banque */
    protected $reglementRemise;
    protected $reglementMontant;
    protected $reglementDate;
    protected $reglementType; /* 1 Acompte 2 Solde */

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
        $this->setReglementMode($CI->managerModesreglement->getModeReglementById($this->getReglementModeId()));

        /* Vérification de l'intégrité du token */
        $security = new Token();
        $chaine = $this->reglementId . $this->reglementMontant . $this->reglementDate . $this->reglementSourceId . $this->reglementModeId;
        $this->reglementSecure = $security->verifyToken($chaine, $this->reglementToken);
    }

    public function hydrateHistorique() {
        /* Recherche de l'historique de ce réglement */
        $CI = & get_instance();
        if ($this->reglementSourceId != $this->reglementId):
            $this->reglementHistorique = $CI->managerReglements->historique($this->reglementGroupeId);
        endif;
    }

    function hydrateClient() {
        if ($this->getReglementClientId() > 0):
            $CI = & get_instance();
            $this->setReglementClient($CI->managerClients->getClientById($this->getReglementClientId()));
        endif;
    }

    function hydrateRemise() {
        if ($this->getReglementRemiseId() > 0):
            $CI = & get_instance();
            $this->setReglementRemise($CI->managerRemises->getRemiseById($this->getReglementRemiseId()));
        endif;
    }

    function getReglementId() {
        return $this->reglementId;
    }

    function getReglementSourceId() {
        return $this->reglementSourceId;
    }

    function getReglementToken() {
        return $this->reglementToken;
    }

    function getReglementSecure() {
        return $this->reglementSecure;
    }

    function getReglementHistorique() {
        return $this->reglementHistorique;
    }

    function getReglementBdcId() {
        return $this->reglementBdcId;
    }

    function getReglementFactureId() {
        return $this->reglementFactureId;
    }

    function getReglementClientId() {
        return $this->reglementClientId;
    }

    function getReglementClient() {
        return $this->reglementClient;
    }

    function getReglementModeId() {
        return $this->reglementModeId;
    }

    function getReglementMode() {
        return $this->reglementMode;
    }

    function getReglementRemiseId() {
        return $this->reglementRemiseId;
    }

    function getReglementRemise() {
        return $this->reglementRemise;
    }

    function getReglementMontant() {
        return $this->reglementMontant;
    }

    function getReglementDate() {
        return $this->reglementDate;
    }

    function getReglementType() {
        return $this->reglementType;
    }

    function setReglementId($reglementId) {
        $this->reglementId = $reglementId;
    }

    function setReglementSourceId($reglementSourceId) {
        $this->reglementSourceId = $reglementSourceId;
    }

    function setReglementToken($reglementToken) {
        $this->reglementToken = $reglementToken;
    }

    function setReglementSecure($reglementSecure) {
        $this->reglementSecure = $reglementSecure;
    }

    function setReglementHistorique($reglementHistorique) {
        $this->reglementHistorique = $reglementHistorique;
    }

    function setReglementBdcId($reglementBdcId) {
        $this->reglementBdcId = $reglementBdcId;
    }

    function setReglementFactureId($reglementFactureId) {
        $this->reglementFactureId = $reglementFactureId;
    }

    function setReglementClientId($reglementClientId) {
        $this->reglementClientId = $reglementClientId;
    }

    function setReglementClient($reglementClient) {
        $this->reglementClient = $reglementClient;
    }

    function setReglementModeId($reglementModeId) {
        $this->reglementModeId = $reglementModeId;
    }

    function setReglementMode($reglementMode) {
        $this->reglementMode = $reglementMode;
    }

    function setReglementRemiseId($reglementRemiseId) {
        $this->reglementRemiseId = $reglementRemiseId;
    }

    function setReglementRemise($reglementRemise) {
        $this->reglementRemise = $reglementRemise;
    }

    function setReglementMontant($reglementMontant) {
        $this->reglementMontant = $reglementMontant;
    }

    function setReglementDate($reglementDate) {
        $this->reglementDate = $reglementDate;
    }

    function setReglementType($reglementType) {
        $this->reglementType = $reglementType;
    }

    function getReglementUtile() {
        return $this->reglementUtile;
    }

    function setReglementUtile($reglementUtile) {
        $this->reglementUtile = $reglementUtile;
    }

    function getReglementGroupeId() {
        return $this->reglementGroupeId;
    }

    function setReglementGroupeId($reglementGroupeId) {
        $this->reglementGroupeId = $reglementGroupeId;
    }

}
