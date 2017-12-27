<?php

/**
 * Classe de gestion des Stocks.
 * Manager : Model_stocks
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Stock {

    protected $stockId;
    protected $stockProduitId;
    protected $stockQte;
    protected $stockBain;
    protected $stockCalibre;
    protected $stockPrixAchat;
    protected $stockEmplacement;   
 

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
    function getStockId() {
        return $this->stockId;
    }

    function getStockProduitId() {
        return $this->stockProduitId;
    }

    function getStockQte() {
        return $this->stockQte;
    }

    function getStockBain() {
        return $this->stockBain;
    }

    function getStockCalibre() {
        return $this->stockCalibre;
    }

    function getStockPrixAchat() {
        return $this->stockPrixAchat;
    }

    function getStockEmplacement() {
        return $this->stockEmplacement;
    }

    function setStockId($stockId) {
        $this->stockId = $stockId;
    }

    function setStockProduitId($stockProduitId) {
        $this->stockProduitId = $stockProduitId;
    }

    function setStockQte($stockQte) {
        $this->stockQte = $stockQte;
    }

    function setStockBain($stockBain) {
        $this->stockBain = $stockBain;
    }

    function setStockCalibre($stockCalibre) {
        $this->stockCalibre = $stockCalibre;
    }

    function setStockPrixAchat($stockPrixAchat) {
        $this->stockPrixAchat = $stockPrixAchat;
    }

    function setStockEmplacement($stockEmplacement) {
        $this->stockEmplacement = $stockEmplacement;
    }   
}
