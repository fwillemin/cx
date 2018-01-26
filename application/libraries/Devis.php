<?php

/**
 * Classe de gestion des Deviss.
 * Manager : Model_devis
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Devis {

    protected $devisId;
    protected $devisEtat; /* 0=>Encours, 1=>Converti en BDC, 2-3-4 => Motifs de perte */
    protected $devisPdvId;
    protected $devisCollaborateurId;
    protected $devisCollaborateur;
    protected $devisDateCreation;
    protected $devisDate;
    protected $devisClientId;
    protected $devisClient;
    protected $devisNbArticles;
    protected $devisTotalHT;
    protected $devisTotalTVA;
    protected $devisTotalTTC;
    protected $devisPoids;
    protected $devisBdcId;
    protected $devisTvas;
    protected $devisArticles;
    protected $devisDelete;

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
        $this->devisCollaborateur = $CI->managerCollaborateurs->getCollaborateurById($this->getDevisCollaborateurId());
        $this->devisClient = $CI->managerClients->getClientById($this->getDevisClientId());

//        if ($CI->managerBdc->liste(array('bdcDevisId' => $this->getDevisId()))):
//            $this->setDevisBdcId($CI->managerBdc->getBdcByDevisId($this->devisId)->getBdcId());
//        else:
//            $this->setDevisBdcId(0);
//        endif;

        $this->devisTvas = $CI->managerDevistva->getTvaByDevisId($this->devisId);
        $bdcLien = $CI->managerBdc->getBdcByDevisId($this->devisId);
        if ($bdcLien):
            $this->devisBdcId = $bdcLien->getBdcId();
        else:
            $this->devisBdcId = 0;
        endif;
    }

    public function hydrateArticles() {
        $CI = & get_instance();
        $this->devisArticles = $CI->managerDevisarticles->getArticlesByDevisId($this->devisId);
    }

    function getDevisId() {
        return $this->devisId;
    }

    function getDevisPdvId() {
        return $this->devisPdvId;
    }

    function getDevisCollaborateurId() {
        return $this->devisCollaborateurId;
    }

    function getDevisCollaborateur() {
        return $this->devisCollaborateur;
    }

    function getDevisDateCreation() {
        return $this->devisDateCreation;
    }

    function getDevisDate() {
        return $this->devisDate;
    }

    function getDevisClientId() {
        return $this->devisClientId;
    }

    function getDevisClient() {
        return $this->devisClient;
    }

    function getDevisNbArticles() {
        return $this->devisNbArticles;
    }

    function getDevisTotalHT() {
        return $this->devisTotalHT;
    }

    function getDevisTotalTVA() {
        return $this->devisTotalTVA;
    }

    function getDevisTotalTTC() {
        return $this->devisTotalTTC;
    }

    function getDevisPoids() {
        return $this->devisPoids;
    }

    function setDevisId($devisId) {
        $this->devisId = $devisId;
    }

    function setDevisPdvId($devisPdvId) {
        $this->devisPdvId = $devisPdvId;
    }

    function setDevisCollaborateurId($devisCollaborateurId) {
        $this->devisCollaborateurId = $devisCollaborateurId;
    }

    function setDevisCollaborateur($devisCollaborateur) {
        $this->devisCollaborateur = $devisCollaborateur;
    }

    function setDevisDateCreation($devisDateCreation) {
        $this->devisDateCreation = $devisDateCreation;
    }

    function setDevisDate($devisDate) {
        $this->devisDate = $devisDate;
    }

    function setDevisClientId($devisClientId) {
        $this->devisClientId = $devisClientId;
    }

    function setDevisClient($devisClient) {
        $this->devisClient = $devisClient;
    }

    function setDevisNbArticles($devisNbArticles) {
        $this->devisNbArticles = $devisNbArticles;
    }

    function setDevisTotalHT($devisTotalHT) {
        $this->devisTotalHT = $devisTotalHT;
    }

    function setDevisTotalTVA($devisTotalTVA) {
        $this->devisTotalTVA = $devisTotalTVA;
    }

    function setDevisTotalTTC($devisTotalTTC) {
        $this->devisTotalTTC = $devisTotalTTC;
    }

    function setDevisPoids($devisPoids) {
        $this->devisPoids = $devisPoids;
    }

    function getDevisBdcId() {
        return $this->devisBdcId;
    }

    function setDevisBdcId($devisBdcId) {
        $this->devisBdcId = $devisBdcId;
    }

    function getDevisTvas() {
        return $this->devisTvas;
    }

    function setDevisTvas($devisTvas) {
        $this->devisTvas = $devisTvas;
    }

    function getDevisEtat() {
        return $this->devisEtat;
    }

    function setDevisEtat($devisEtat) {
        $this->devisEtat = $devisEtat;
    }

    function getDevisArticles() {
        return $this->devisArticles;
    }

    function setDevisArticles($devisArticles) {
        $this->devisArticles = $devisArticles;
    }

    function getDevisDelete() {
        return $this->devisDelete;
    }

    function setDevisDelete($devisDelete) {
        $this->devisDelete = $devisDelete;
    }

}
