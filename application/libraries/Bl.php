<?php

/**
 * Classe de gestion des Bls.
 * Manager : Model_bls
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Bl {

    protected $blId;
    protected $blPdvId;
    protected $blBdcId;
    protected $blDate;
    protected $blFactureId;
    protected $blFacture;
    protected $blLivraisons;
    protected $blTvas;
    protected $blDelete;
    /* Chargement optionnels */
    protected $blClientId;
    protected $blClient;

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
        $this->setBlTvas($CI->managerBltva->getTvaByBlId($this->getBlId()));
    }

    function hydrateClient() {
        if ($this->getBlClientId()):
            $CI = & get_instance();
            $this->setBlClient($CI->managerClients->getClientById($this->getBlClientId()));
        endif;
    }

    function hydrateFacture() {
        if ($this->getBlFactureId()):
            $CI = & get_instance();
            $this->setBlFacture($CI->managerFactures->getFactureById($this->getBlFactureId()));
        endif;
    }

    function hydrateLivraisons() {
        $CI = & get_instance();
        $livraisons = $CI->managerLivraisons->getLivraisonByBlId($this->getBlId());
        $this->setBlLivraisons($livraisons);
    }

    function getBlId() {
        return $this->blId;
    }

    function getBlPdvId() {
        return $this->blPdvId;
    }

    function getBlBdcId() {
        return $this->blBdcId;
    }

    function getBlDate() {
        return $this->blDate;
    }

    function getBlFactureId() {
        return $this->blFactureId;
    }

    function setBlId($blId) {
        $this->blId = $blId;
    }

    function setBlPdvId($blPdvId) {
        $this->blPdvId = $blPdvId;
    }

    function setBlBdcId($blBdcId) {
        $this->blBdcId = $blBdcId;
    }

    function setBlDate($blDate) {
        $this->blDate = $blDate;
    }

    function setBlFactureId($blFactureId) {
        $this->blFactureId = $blFactureId;
    }

    function getBlLivraisons() {
        return $this->blLivraisons;
    }

    function setBlLivraisons($blLivraisons) {
        $this->blLivraisons = $blLivraisons;
    }

    function getBlFacture() {
        return $this->blFacture;
    }

    function setBlFacture($blFacture) {
        $this->blFacture = $blFacture;
    }

    function getBlTvas() {
        return $this->blTvas;
    }

    function setBlTvas($bltvas) {
        $this->blTvas = $bltvas;
    }

    function getBlClientId() {
        return $this->blClientId;
    }

    function getBlClient() {
        return $this->blClient;
    }

    function setBlClientId($blClientId) {
        $this->blClientId = $blClientId;
    }

    function setBlClient($blClient) {
        $this->blClient = $blClient;
    }

    function getBlDelete() {
        return $this->blDelete;
    }

    function setBlDelete($blDelete) {
        $this->blDelete = $blDelete;
    }

}
