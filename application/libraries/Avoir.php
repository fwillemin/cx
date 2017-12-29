<?php

/**
 * Classe de gestion des Avoirs.
 * Manager : Model_avoirs
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Avoir {

    protected $avoirId;
    protected $avoirPdvId;
    protected $avoirDate;
    protected $avoirClientId;
    protected $avoirClient;
    protected $avoirTotalHT;
    protected $avoirTotalTVA;
    protected $avoirTotalTTC;
    protected $avoirReglements; /* Liste des réglements pour l'avoir */
    protected $avoirLignes;
    protected $avoirTvas;
    protected $avoirFactureId;

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

        $this->avoirLignes = $CI->managerAvoirlignes->getLignesByAvoirId($this);
        $this->avoirTvas = $CI->managerAvoirtva->getTvaByAvoirId($this->avoirId);
    }

    function hydrateClient() {
        $CI = & get_instance();
        $this->avoirClient = $CI->managerClients->getClientById($this->avoirClientId);
    }

    function hydrateReglements() {
        $CI = & get_instance();
        $this->avoirReglements = $CI->managerReglements->getReglementsByAvoirId($this);
    }

    function getAvoirId() {
        return $this->avoirId;
    }

    function getAvoirPdvId() {
        return $this->avoirPdvId;
    }

    function getAvoirDate() {
        return $this->avoirDate;
    }

    function getAvoirClientId() {
        return $this->avoirClientId;
    }

    function getAvoirClient() {
        return $this->avoirClient;
    }

    function getAvoirTotalHT() {
        return $this->avoirTotalHT;
    }

    function getAvoirTotalTVA() {
        return $this->avoirTotalTVA;
    }

    function getAvoirTotalTTC() {
        return $this->avoirTotalTTC;
    }

    function getAvoirReglements() {
        return $this->avoirReglements;
    }

    function getAvoirLignes() {
        return $this->avoirLignes;
    }

    function getAvoirTvas() {
        return $this->avoirTvas;
    }

    function getAvoirFactureId() {
        return $this->avoirFactureId;
    }

    function setAvoirId($avoirId) {
        $this->avoirId = $avoirId;
    }

    function setAvoirPdvId($avoirPdvId) {
        $this->avoirPdvId = $avoirPdvId;
    }

    function setAvoirDate($avoirDate) {
        $this->avoirDate = $avoirDate;
    }

    function setAvoirClientId($avoirClientId) {
        $this->avoirClientId = $avoirClientId;
    }

    function setAvoirClient($avoirClient) {
        $this->avoirClient = $avoirClient;
    }

    function setAvoirTotalHT($avoirTotalHT) {
        $this->avoirTotalHT = $avoirTotalHT;
    }

    function setAvoirTotalTVA($avoirTotalTVA) {
        $this->avoirTotalTVA = $avoirTotalTVA;
    }

    function setAvoirTotalTTC($avoirTotalTTC) {
        $this->avoirTotalTTC = $avoirTotalTTC;
    }

    function setAvoirReglements($avoirReglements) {
        $this->avoirReglements = $avoirReglements;
    }

    function setAvoirLignes($avoirLignes) {
        $this->avoirLignes = $avoirLignes;
    }

    function setAvoirTvas($avoirTvas) {
        $this->avoirTvas = $avoirTvas;
    }

    function setAvoirFactureId($avoirFactureId) {
        $this->avoirFactureId = $avoirFactureId;
    }

}
