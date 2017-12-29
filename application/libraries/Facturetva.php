<?php

/**
 * Classe de gestion des Factures.
 * Manager : Model_factures
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Facturetva {

    protected $tvaFactureId;
    protected $tvaTaux;
    protected $tvaMontant;   

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
    
    function getTvaFactureId() {
        return $this->tvaFactureId;
    }

    function getTvaTaux() {
        return $this->tvaTaux;
    }

    function getTvaMontant() {
        return $this->tvaMontant;
    }

    function setTvaFactureId($tvaFactureId) {
        $this->tvaFactureId = $tvaFactureId;
    }

    function setTvaTaux($tvaTaux) {
        $this->tvaTaux = $tvaTaux;
    }

    function setTvaMontant($tvaMontant) {
        $this->tvaMontant = $tvaMontant;
    }    
}
