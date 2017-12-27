<?php

/**
 * Classe de gestion des Bls.
 * Manager : Model_livraisons
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Livraison {

    protected $livraisonId;
    protected $livraisonBlId;
    protected $livraisonBl;
    protected $livraisonArticleId;
    protected $livraisonArticle;
    protected $livraisonStockId;
    protected $livraisonQte;    

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
        $this->setLivraisonArticle($CI->managerBdcarticles->getArticleById( $this->getLivraisonArticleId() ));
    }
    
    function hydrateBl() {
        $CI =& get_instance();
        $this->livraisonBl = $CI->managerBls->getBlById( $this->livraisonBlId );
    }
    
    function getLivraisonId() {
        return $this->livraisonId;
    }

    function getLivraisonBlId() {
        return $this->livraisonBlId;
    }

    function getLivraisonArticleId() {
        return $this->livraisonArticleId;
    }

    function getLivraisonStockId() {
        return $this->livraisonStockId;
    }

    function getLivraisonQte() {
        return $this->livraisonQte;
    }

    function setLivraisonId($livraisonId) {
        $this->livraisonId = $livraisonId;
    }

    function setLivraisonBlId($livraisonBlId) {
        $this->livraisonBlId = $livraisonBlId;
    }

    function setLivraisonArticleId($livraisonArticleId) {
        $this->livraisonArticleId = $livraisonArticleId;
    }

    function setLivraisonStockId($livraisonStockId) {
        $this->livraisonStockId = $livraisonStockId;
    }

    function setLivraisonQte($livraisonQte) {
        $this->livraisonQte = $livraisonQte;
    }
    function getLivraisonArticle() {
        return $this->livraisonArticle;
    }

    function setLivraisonArticle($livraisonArticle) {
        $this->livraisonArticle = $livraisonArticle;
    }
    function getLivraisonBl() {
        return $this->livraisonBl;
    }

    function setLivraisonBl($livraisonBl) {
        $this->livraisonBl = $livraisonBl;
    }
}
