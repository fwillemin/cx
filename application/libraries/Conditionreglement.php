<?php

/**
 * Classe de gestion des Conditionreglements.
 * Manager : Conditionl_conditionreglements
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Conditionreglement {

    protected $conditionreglementId;
    protected $conditionreglementNom;
   
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
    function getConditionreglementId() {
        return $this->conditionreglementId;
    }

    function getConditionreglementNom() {
        return $this->conditionreglementNom;
    }

    function setConditionreglementId($conditionreglementId) {
        $this->conditionreglementId = $conditionreglementId;
    }

    function setConditionreglementNom($conditionreglementNom) {
        $this->conditionreglementNom = $conditionreglementNom;
    }
}
