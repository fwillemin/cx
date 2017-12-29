<?php

/**
 * Classe de gestion des Clotures de caisse
 * Manager : Model_clotures
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Cloture {

    protected $clotureId;
    protected $clotureDate;
    protected $clotureType;
    protected $clotureMontant;
    protected $clotureToken;
    protected $clotureSecure;

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

        $security = new Token();
        $chaine = $this->clotureDate . $this->clotureMontant . $this->clotureType;
        $this->clotureSecure = $security->verifyToken($chaine, $this->clotureToken);
    }

    function getClotureId() {
        return $this->clotureId;
    }

    function getClotureDate() {
        return $this->clotureDate;
    }

    function getClotureType() {
        return $this->clotureType;
    }

    function getClotureMontant() {
        return $this->clotureMontant;
    }

    function getClotureToken() {
        return $this->clotureToken;
    }

    function setClotureId($clotureId) {
        $this->clotureId = $clotureId;
    }

    function setClotureDate($clotureDate) {
        $this->clotureDate = $clotureDate;
    }

    function setClotureType($clotureType) {
        $this->clotureType = $clotureType;
    }

    function setClotureMontant($clotureMontant) {
        $this->clotureMontant = $clotureMontant;
    }

    function setClotureToken($clotureToken) {
        $this->clotureToken = $clotureToken;
    }
    function getClotureSecure() {
        return $this->clotureSecure;
    }

    function setClotureSecure($clotureSecure) {
        $this->clotureSecure = $clotureSecure;
    }


}
