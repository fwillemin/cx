<?php

/**
 * Classe de gestion des Caisses.
 * Manager : Model_caisses
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Caisse {

    protected $caisseId;
    protected $caissePdvId;
    protected $caisseDate;
    protected $caisseMontant;
    protected $caisseType;
    protected $caisseDetail;
    

    public function __construct(array $valeurs = []) {
        /* Si on passe des valeurs, on hydrate l'objet */
        if(!empty($valeurs)) $this->hydrate ($valeurs);        
    }
        
    public function hydrate(array $donnees) {
       foreach ($donnees as $key => $value):
            $method = 'set'.ucfirst($key);
            if(method_exists($this, $method))
                $this->$method($value);
        endforeach;      
    }
    function getCaisseId() {
        return $this->caisseId;
    }

    function getCaissePdvId() {
        return $this->caissePdvId;
    }

    function getCaisseDate() {
        return $this->caisseDate;
    }

    function getCaisseMontant() {
        return $this->caisseMontant;
    }

    function getCaisseType() {
        return $this->caisseType;
    }

    function getCaisseDetail() {
        return $this->caisseDetail;
    }

    function setCaisseId($caisseId) {
        $this->caisseId = $caisseId;
    }

    function setCaissePdvId($caissePdvId) {
        $this->caissePdvId = $caissePdvId;
    }

    function setCaisseDate($caisseDate) {
        $this->caisseDate = $caisseDate;
    }

    function setCaisseMontant($caisseMontant) {
        $this->caisseMontant = $caisseMontant;
    }

    function setCaisseType($caisseType) {
        $this->caisseType = $caisseType;
    }

    function setCaisseDetail($caisseDetail) {
        $this->caisseDetail = $caisseDetail;
    }
}
