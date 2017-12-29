<?php

/**
 * Classe de gestion des Familles.
 * Manager : Model_familles
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Famille {

    protected $familleId;
    protected $famillePdvId;
    protected $familleNom;    

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
    }
    function getFamilleId() {
        return $this->familleId;
    }

    function getFamillePdvId() {
        return $this->famillePdvId;
    }

    function getFamilleNom() {
        return $this->familleNom;
    }

    function setFamilleId($familleId) {
        $this->familleId = $familleId;
    }

    function setFamillePdvId($famillePdvId) {
        $this->famillePdvId = $famillePdvId;
    }

    function setFamilleNom($familleNom) {
        $this->familleNom = $familleNom;
    }
}
