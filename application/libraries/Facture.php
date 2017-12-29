<?php

/**
 * Classe de gestion des Factures.
 * Manager : Model_factures
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Facture {

    protected $factureId;
    protected $facturePdvId;
    protected $factureDate;
    protected $factureClientId;
    protected $factureClient;
    protected $factureTotalHT;
    protected $factureTotalTVA;
    protected $factureTotalTTC;
    protected $factureDelete;
    protected $factureSolde;
    protected $factureEcheance;
    protected $factureReglements; /* Liste des réglements pour la facture */
    protected $factureConditionsReglementId;
    protected $factureConditionsReglement;
    protected $factureLignes;
    protected $factureTvas;
    protected $factureBls;
    protected $factureAvoirs;
    protected $factureTotalAvoirs;

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
        $this->factureConditionsReglement = $CI->managerConditionsreglement->getConditionReglementById($this->getFactureConditionsReglementId());
        $this->factureLignes = $CI->managerFacturelignes->getLignesByFactureId($this);
        $this->factureTvas = $CI->managerFacturetva->getTvaByFactureId($this->factureId);
        $this->factureBls = $CI->managerBls->getBlByFactureId($this->factureId);
    }

    function hydrateClient() {
        $CI = & get_instance();
        $this->factureClient = $CI->managerClients->getClientById($this->factureClientId);
    }

    function hydrateReglements() {
        $CI = & get_instance();
        $this->factureReglements = $CI->managerReglements->getReglementsByFactureId($this);
    }

    function hydrateAvoirs() {
        $CI = & get_instance();
        $this->factureAvoirs = $CI->managerAvoirs->getAvoirsByFactureId($this->factureId);
        $this->factureTotalAvoirs = 0;
        if ($this->factureAvoirs):
            foreach ($this->factureAvoirs as $a):
                $this->factureTotalAvoirs += $a->getAvoirTotalHT();
            endforeach;
        endif;
    }

    function solde() {
        $totalPaye = 0;
        $this->hydrateReglements();
        if (!empty($this->factureReglements)):
            foreach ($this->factureReglements as $r):
                $totalPaye += $r->getReglementMontant();
            endforeach;
        endif;

        $this->hydrateAvoirs();
        if (!empty($this->factureAvoirs)):
            foreach ($this->factureAvoirs as $a):
                $totalPaye += $a->getAvoirTotalTTC();
            endforeach;
        endif;
        $this->setFactureSolde(round($this->getFactureTotalTTC() - $totalPaye, 2));
    }

    function getFactureId() {
        return $this->factureId;
    }

    function getFacturePdvId() {
        return $this->facturePdvId;
    }

    function getFactureDate() {
        return $this->factureDate;
    }

    function getFactureClientId() {
        return $this->factureClientId;
    }

    function getFactureClient() {
        return $this->factureClient;
    }

    function getFactureTotalHT() {
        return $this->factureTotalHT;
    }

    function getFactureTotalTVA() {
        return $this->factureTotalTVA;
    }

    function getFactureTotalTTC() {
        return $this->factureTotalTTC;
    }

    function getFactureDelete() {
        return $this->factureDelete;
    }

    function getFactureSolde() {
        return $this->factureSolde;
    }

    function getFactureEcheance() {
        return $this->factureEcheance;
    }

    function getFactureReglements() {
        return $this->factureReglements;
    }

    function getFactureConditionsReglementId() {
        return $this->factureConditionsReglementId;
    }

    function getFactureConditionsReglement() {
        return $this->factureConditionsReglement;
    }

    function getFactureLignes() {
        return $this->factureLignes;
    }

    function getFactureTvas() {
        return $this->factureTvas;
    }

    function setFactureId($factureId) {
        $this->factureId = $factureId;
    }

    function setFacturePdvId($facturePdvId) {
        $this->facturePdvId = $facturePdvId;
    }

    function setFactureDate($factureDate) {
        $this->factureDate = $factureDate;
    }

    function setFactureClientId($factureClientId) {
        $this->factureClientId = $factureClientId;
    }

    function setFactureClient($factureClient) {
        $this->factureClient = $factureClient;
    }

    function setFactureTotalHT($factureTotalHT) {
        $this->factureTotalHT = $factureTotalHT;
    }

    function setFactureTotalTVA($factureTotalTVA) {
        $this->factureTotalTVA = $factureTotalTVA;
    }

    function setFactureTotalTTC($factureTotalTTC) {
        $this->factureTotalTTC = $factureTotalTTC;
    }

    function setFactureDelete($factureDelete) {
        $this->factureDelete = $factureDelete;
    }

    function setFactureSolde($factureSolde) {
        $this->factureSolde = $factureSolde;
    }

    function setFactureEcheance($factureEcheance) {
        $this->factureEcheance = $factureEcheance;
    }

    function setFactureReglements($factureReglements) {
        $this->factureReglements = $factureReglements;
    }

    function setFactureConditionsReglementId($factureConditionsReglementId) {
        $this->factureConditionsReglementId = $factureConditionsReglementId;
    }

    function setFactureConditionsReglement($factureConditionsReglement) {
        $this->factureConditionsReglement = $factureConditionsReglement;
    }

    function setFacturelignes($factureLignes) {
        $this->factureLignes = $factureLignes;
    }

    function setFactureTvas($factureTvas) {
        $this->factureTvas = $factureTvas;
    }

    function getFactureBls() {
        return $this->factureBls;
    }

    function setFactureBls($factureBls) {
        $this->factureBls = $factureBls;
    }

    function getFactureAvoirs() {
        return $this->factureAvoirs;
    }

    function setFactureAvoirs($factureAvoirs) {
        $this->factureAvoirs = $factureAvoirs;
    }

    function getFactureTotalAvoirs() {
        return $this->factureTotalAvoirs;
    }

    function setFactureTotalAvoirs($factureTotalAvoirs) {
        $this->factureTotalAvoirs = $factureTotalAvoirs;
    }

}
