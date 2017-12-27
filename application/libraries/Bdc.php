<?php

/**
 * Classe de gestion des Bdcs.
 * Manager : Model_bdcs
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Bdc {

    protected $bdcId;
    protected $bdcPdvId;
    protected $bdcCollaborateurId;
    protected $bdcCollaborateur;
    protected $bdcDevisId;
    protected $bdcDevis;
    protected $bdcDateCreation;
    protected $bdcDate;
    protected $bdcClientId;
    protected $bdcClient;
    protected $bdcNbArticles;
    protected $bdcTotalHT;
    protected $bdcTotalTVA;
    protected $bdcTotalTTC;
    protected $bdcPoids;
    protected $bdcEtat;
    protected $bdcCommentaire;
    protected $bdcArticles;
    protected $bdcAvancement;
    protected $bdcTvas;
    protected $bdcDelete;

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
        $this->setBdcCollaborateur($CI->managerCollaborateurs->getCollaborateurById($this->getBdcCollaborateurId()));
        $this->setBdcClient($CI->managerClients->getClientById($this->getBdcClientId()));
        $this->setBdcTvas($CI->managerBdctva->getTvaByBdcId($this->getBdcId()));
    }

    public function hydrateDevis() {
        $CI = & get_instance();
        $this->bdcDevis = $CI->managerDevis->getDevisById($this->bdcDevisId, 'array');
    }

    public function hydrateArticles() {
        $CI = & get_instance();
        $this->bdcArticles = $CI->managerBdcarticles->getArticlesByBdcId($this->getBdcId());
    }

    public function majEtat() {
        $partiel = FALSE;
        $complet = TRUE;
        if (!isset($this->bdcArticles)):
            $this->hydrateArticles();
        endif;
        foreach ($this->getBdcArticles() as $a):
            if ($a->getArticleQteLivree() > 0)
                $partiel = TRUE;
            if ($a->getArticleQte() > $a->getArticleQteLivree())
                $complet = FALSE;
        endforeach;

        if ($complet === TRUE):
            $this->setBdcEtat(2);
        elseif ($partiel === TRUE):
            $this->setBdcEtat(1);
        else:
            $this->setBdcEtat(0);
        endif;
    }

    /**
     * Calcule l'avancement du bon de commande
     */
    function setAvancement() {
        $nbOk = 0;
        $nbManuel = 0;
        $nbHC = 0;
        if (!isset($this->bdcArticles)):
            $this->hydrateArticles();
        endif;
        $nbArticles = count($this->getBdcArticles());
        foreach ($this->getBdcArticles() as $a):
            switch ($a->getArticleApproId()):
                case 999999999:
                    $nbManuel++;
                    break;
                case 1000000000:
                    $nbOk++;
                    break;
                case 0:
                    if ($a->getArticleProduitId() == 0):
                        $nbHC++;
                    endif;
                    break;
                default:
                    if ($a->getArticleAppro()->getApproQteRecu() > 0)
                        $nbOk++;
                    break;
            endswitch;
        endforeach;
        $this->setBdcAvancement(array(round(($nbHC / $nbArticles) * 100), round(($nbManuel / $nbArticles) * 100), round(($nbOk / $nbArticles) * 100)));
    }

    function getBdcId() {
        return $this->bdcId;
    }

    function getBdcPdvId() {
        return $this->bdcPdvId;
    }

    function getBdcCollaborateurId() {
        return $this->bdcCollaborateurId;
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

    function setBdcCollaborateurId($bdcCollaborateurId) {
        $this->bdcCollaborateurId = $bdcCollaborateurId;
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

    function setBdcPoids($bdcPoids) {
        $this->bdcPoids = $bdcPoids;
    }

    function setBdcEtat($bdcEtat) {
        $this->bdcEtat = $bdcEtat;
    }

    function setBdcCommentaire($bdcCommentaire) {
        $this->bdcCommentaire = $bdcCommentaire;
    }

    function getBdcCollaborateur() {
        return $this->bdcCollaborateur;
    }

    function setBdcCollaborateur($bdcCollaborateur) {
        $this->bdcCollaborateur = $bdcCollaborateur;
    }

    function getBdcClient() {
        return $this->bdcClient;
    }

    function setBdcClient($bdcClient) {
        $this->bdcClient = $bdcClient;
    }

    function getBdcArticles() {
        return $this->bdcArticles;
    }

    function setBdcArticles($bdcArticles) {
        $this->bdcArticles = $bdcArticles;
    }

    function getBdcAvancement() {
        return $this->bdcAvancement;
    }

    function setBdcAvancement($bdcAvancement) {
        $this->bdcAvancement = $bdcAvancement;
    }

    function getBdcTvas() {
        return $this->bdcTvas;
    }

    function setBdcTvas($bdcTvas) {
        $this->bdcTvas = $bdcTvas;
    }

    function getBdcDelete() {
        return $this->bdcDelete;
    }

    function setBdcDelete($bdcDelete) {
        $this->bdcDelete = $bdcDelete;
    }

    function getBdcDevis() {
        return $this->bdcDevis;
    }

    function setBdcDevis($bdcDevis) {
        $this->bdcDevis = $bdcDevis;
    }

}
