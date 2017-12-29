<?php

/**
 * Classe de gestion des Usines.
 * Manager : Model_usines
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Usine {

    protected $usineId;
    protected $usinePdvId;
    protected $usineNom;
    protected $usineEmail;
    protected $usineCodeClient;

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
    function getUsineId() {
        return $this->usineId;
    }

    function getUsinePdvId() {
        return $this->usinePdvId;
    }

    function getUsineNom() {
        return $this->usineNom;
    }

    function getUsineEmail() {
        return $this->usineEmail;
    }

    function getUsineCodeClient() {
        return $this->usineCodeClient;
    }

    function setUsineId($usineId) {
        $this->usineId = $usineId;
    }

    function setUsinePdvId($usinePdvId) {
        $this->usinePdvId = $usinePdvId;
    }

    function setUsineNom($usineNom) {
        $this->usineNom = $usineNom;
    }

    function setUsineEmail($usineEmail) {
        $this->usineEmail = $usineEmail;
    }

    function setUsineCodeClient($usineCodeClient) {
        $this->usineCodeClient = $usineCodeClient;
    }    
}
