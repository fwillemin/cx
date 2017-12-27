<?php

/**
 * Classe de gestion des Deviss.
 * Manager : Model_deviss
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Devistva {

    protected $tvaDevisId;
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
    
    function getTvaDevisId() {
        return $this->tvaDevisId;
    }

    function getTvaTaux() {
        return $this->tvaTaux;
    }

    function getTvaMontant() {
        return $this->tvaMontant;
    }

    function setTvaDevisId($tvaDevisId) {
        $this->tvaDevisId = $tvaDevisId;
    }

    function setTvaTaux($tvaTaux) {
        $this->tvaTaux = $tvaTaux;
    }

    function setTvaMontant($tvaMontant) {
        $this->tvaMontant = $tvaMontant;
    }    
}
