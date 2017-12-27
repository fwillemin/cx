<?php

/**
 * Classe de gestion des Pdvs.
 * Manager : Model_pdv
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Pdv {

    protected $pdvId;
    protected $pdvRaisonSociale;
    protected $pdvNomCommercial;
    protected $pdvSiren;
    protected $pdvTvaIntracom;
    protected $pdvApe;
    protected $pdvAdresse1;
    protected $pdvAdresse2;
    protected $pdvCp;
    protected $pdvVille;
    protected $pdvTelephone;
    protected $pdvEmail;
    protected $pdvFax;
    protected $pdvWww;
    protected $pdvTelephoneCommercial;
    protected $pdvEmailCommercial;
    protected $pdvTelephoneTechnique;
    protected $pdvEmailTechnique;
    protected $pdvLogo;

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
    }

    function getPdvId() {
        return $this->pdvId;
    }

    function getPdvRaisonSociale() {
        return $this->pdvRaisonSociale;
    }

    function getPdvNomCommercial() {
        return $this->pdvNomCommercial;
    }

    function getPdvSiren() {
        return $this->pdvSiren;
    }

    function getPdvAdresse1() {
        return $this->pdvAdresse1;
    }

    function getPdvAdresse2() {
        return $this->pdvAdresse2;
    }

    function getPdvCp() {
        return $this->pdvCp;
    }

    function getPdvVille() {
        return $this->pdvVille;
    }

    function getPdvTelephone() {
        return $this->pdvTelephone;
    }

    function getPdvEmail() {
        return $this->pdvEmail;
    }

    function getPdvFax() {
        return $this->pdvFax;
    }

    function getPdvTelephoneCommercial() {
        return $this->pdvTelephoneCommercial;
    }

    function getPdvEmailCommercial() {
        return $this->pdvEmailCommercial;
    }

    function getPdvTelephoneTechnique() {
        return $this->pdvTelephoneTechnique;
    }

    function getPdvEmailTechnique() {
        return $this->pdvEmailTechnique;
    }

    function getPdvLogo() {
        return $this->pdvLogo;
    }

    function setPdvId($pdvId) {
        $this->pdvId = $pdvId;
    }

    function setPdvRaisonSociale($pdvRaisonSociale) {
        $this->pdvRaisonSociale = $pdvRaisonSociale;
    }

    function setPdvNomCommercial($pdvNomCommercial) {
        $this->pdvNomCommercial = $pdvNomCommercial;
    }

    function setPdvSiren($pdvSiren) {
        $this->pdvSiren = $pdvSiren;
    }

    function setPdvAdresse1($pdvAdresse1) {
        $this->pdvAdresse1 = $pdvAdresse1;
    }

    function setPdvAdresse2($pdvAdresse2) {
        $this->pdvAdresse2 = $pdvAdresse2;
    }

    function setPdvCp($pdvCp) {
        $this->pdvCp = $pdvCp;
    }

    function setPdvVille($pdvVille) {
        $this->pdvVille = $pdvVille;
    }

    function setPdvTelephone($pdvTelephone) {
        $this->pdvTelephone = $pdvTelephone;
    }

    function setPdvEmail($pdvEmail) {
        $this->pdvEmail = $pdvEmail;
    }

    function setPdvFax($pdvFax) {
        $this->pdvFax = $pdvFax;
    }

    function setPdvTelephoneCommercial($pdvTelephoneCommercial) {
        $this->pdvTelephoneCommercial = $pdvTelephoneCommercial;
    }

    function setPdvEmailCommercial($pdvEmailCommercial) {
        $this->pdvEmailCommercial = $pdvEmailCommercial;
    }

    function setPdvTelephoneTechnique($pdvTelephoneTechnique) {
        $this->pdvTelephoneTechnique = $pdvTelephoneTechnique;
    }

    function setPdvEmailTechnique($pdvEmailTechnique) {
        $this->pdvEmailTechnique = $pdvEmailTechnique;
    }

    function setPdvLogo($pdvLogo) {
        $this->pdvLogo = $pdvLogo;
    }

    function getPdvTvaIntracom() {
        return $this->pdvTvaIntracom;
    }

    function setPdvTvaIntracom($pdvTvaIntracom) {
        $this->pdvTvaIntracom = $pdvTvaIntracom;
    }

    function getPdvApe() {
        return $this->pdvApe;
    }

    function setPdvApe($pdvApe) {
        $this->pdvApe = $pdvApe;
    }

    function getPdvWww() {
        return $this->pdvWww;
    }

    function setPdvWww($pdvWww) {
        $this->pdvWww = $pdvWww;
    }

}
