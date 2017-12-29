<?php

/**
 * Classe de gestion des Bls.
 * Manager : Model_lignes
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Factureligne {

    protected $ligneId;
    protected $ligneFactureId;
    protected $ligneBlId;
    protected $ligneLivraisonId;
    protected $ligneProduitId;
    protected $ligneUniteId;
    protected $ligneQte;
    protected $ligneDesignation;
    protected $lignePrixUnitaire;
    protected $ligneTauxTVA;
    protected $ligneRemise;
    protected $lignePrixNet;
    protected $ligneTotalHT;

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
    }

    function getLigneId() {
        return $this->ligneId;
    }

    function getLigneFactureId() {
        return $this->ligneFactureId;
    }

    function getLigneBlId() {
        return $this->ligneBlId;
    }

    function getLigneLivraisonId() {
        return $this->ligneLivraisonId;
    }

    function getLigneProduitId() {
        return $this->ligneProduitId;
    }

    function getLigneUniteId() {
        return $this->ligneUniteId;
    }

    function getLigneQte() {
        return $this->ligneQte;
    }

    function getLigneDesignation() {
        return $this->ligneDesignation;
    }

    function getLignePrixUnitaire() {
        return $this->lignePrixUnitaire;
    }

    function getLigneTauxTVA() {
        return $this->ligneTauxTVA;
    }

    function getLigneRemise() {
        return $this->ligneRemise;
    }

    function getLignePrixNet() {
        return $this->lignePrixNet;
    }

    function getLigneTotalHT() {
        return $this->ligneTotalHT;
    }

    function setLigneId($ligneId) {
        $this->ligneId = $ligneId;
    }

    function setLigneFactureId($ligneFactureId) {
        $this->ligneFactureId = $ligneFactureId;
    }

    function setLigneBlId($ligneBlId) {
        $this->ligneBlId = $ligneBlId;
    }

    function setLigneLivraisonId($ligneLivraisonId) {
        $this->ligneLivraisonId = $ligneLivraisonId;
    }

    function setLigneProduitId($ligneProduitId) {
        $this->ligneProduitId = $ligneProduitId;
    }

    function setLigneUniteId($ligneUniteId) {
        $this->ligneUniteId = $ligneUniteId;
    }

    function setLigneQte($ligneQte) {
        $this->ligneQte = $ligneQte;
    }

    function setLigneDesignation($ligneDesignation) {
        $this->ligneDesignation = $ligneDesignation;
    }

    function setLignePrixUnitaire($lignePrixUnitaire) {
        $this->lignePrixUnitaire = $lignePrixUnitaire;
    }

    function setLigneTauxTVA($ligneTauxTVA) {
        $this->ligneTauxTVA = $ligneTauxTVA;
    }

    function setLigneRemise($ligneRemise) {
        $this->ligneRemise = $ligneRemise;
    }

    function setLignePrixNet($lignePrixNet) {
        $this->lignePrixNet = $lignePrixNet;
    }

    function setLigneTotalHT($ligneTotalHT) {
        $this->ligneTotalHT = $ligneTotalHT;
    }

}
