<?php

/**
 * Classe de gestion des Remises.
 * Manager : Model_remises
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Remise {

    protected $remiseId;
    protected $remisePdvId;
    protected $remiseDate;
    protected $remiseTotal;
    protected $remiseBanque;
    protected $remiseReglements;

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
        
        $CI =& get_instance(); 
        $this->setRemiseReglements( $CI->managerReglements->getReglementsByRemiseId( $this->getRemiseId() ) );
    }
    function getRemiseId() {
        return $this->remiseId;
    }

    function getRemisePdvId() {
        return $this->remisePdvId;
    }

    function getRemiseDate() {
        return $this->remiseDate;
    }

    function getRemiseTotal() {
        return $this->remiseTotal;
    }

    function getRemiseBanque() {
        return $this->remiseBanque;
    }

    function setRemiseId($remiseId) {
        $this->remiseId = $remiseId;
    }

    function setRemisePdvId($remisePdvId) {
        $this->remisePdvId = $remisePdvId;
    }

    function setRemiseDate($remiseDate) {
        $this->remiseDate = $remiseDate;
    }

    function setRemiseTotal($remiseTotal) {
        $this->remiseTotal = $remiseTotal;
    }

    function setRemiseBanque($remiseBanque) {
        $this->remiseBanque = $remiseBanque;
    }
    function getRemiseReglements() {
        return $this->remiseReglements;
    }

    function setRemiseReglements($remiseReglements) {
        $this->remiseReglements = $remiseReglements;
    }
}
