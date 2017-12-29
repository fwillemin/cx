<?php

/**
 * Classe de gestion des Modereglements.
 * Manager : Model_modereglements
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Modereglement {

    protected $modereglementId;
    protected $modereglementNom;
   
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
    function getModereglementId() {
        return $this->modereglementId;
    }

    function getModereglementNom() {
        return $this->modereglementNom;
    }

    function setModereglementId($modereglementId) {
        $this->modereglementId = $modereglementId;
    }

    function setModereglementNom($modereglementNom) {
        $this->modereglementNom = $modereglementNom;
    }
}
