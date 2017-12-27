<?php

/**
 * Classe de gestion des Produits.
 * Manager : Model_produits
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Produit {

    protected $produitId;
    protected $produitPdvId;
    protected $produitEAN;
    protected $produitRefUsine;
    protected $produitDesignation;
    protected $produitUsineId;
    protected $produitUsine;
    protected $produitFamilleId;
    protected $produitFamille;
    protected $produitUniteId;
    protected $produitUnite;
    protected $produitMultiple;
    protected $produitPrixAchatUnitaire;
    protected $produitPrixAchatPalette;
    protected $produitSeuilPalette;
    protected $produitPrixVente;
    protected $produitPoids;
    protected $produitGestionStock;
    protected $produitGestionBain;
    protected $produitTVA; /* Taux de TVA à appliquer */
    /*------*/
    protected $produitEncours; /* Quantité encours de commande et non livrée pour ce produit */
    protected $produitDispo; /* Quantité de produit disponible */
    protected $produitStock; /* Stock physique en entrepot */
    protected $produitEnCommande; /* Qté en commande chez le fournisseur */
    protected $produitStocks;
    
    protected $produitArchive;
    protected $produitVendu; /* BOOL pour indiqué si ce produit est dans une vente. */

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
        $this->setProduitEncours( $CI->managerBdcarticles->encoursByProduitId( $this->getProduitId() ) );
        $this->setProduitStock( round( $CI->managerStocks->stockTotalProduit( $this->getProduitId() ), 2) );
        $this->setProduitDispo( round( $this->getProduitStock() - $this->getProduitEncours(),2) );
        $this->setProduitStocks( $CI->managerStocks->getStocksByProduitId( $this->getproduitId() ) );
        $this->setProduitUsine( $CI->managerUsines->getUsineById( $this->getProduitUsineId() ) );
        $this->setProduitFamille( $CI->managerFamilles->getFamilleById( $this->getProduitFamilleId() ) );
    }
    
    function checkVentes() {
        
        $CI =& get_instance(); 
        $nbDevis = $CI->managerDevisarticles->count(array( 'articleProduitId' => $this->produitId ));
        $nbBdc = $CI->managerBdcarticles->count(array( 'articleProduitId' => $this->produitId ));
        $nbFacture = $CI->managerFacturelignes->count(array( 'ligneProduitId' => $this->produitId ));
        
        if( $nbBdc + $nbDevis + $nbFacture > 0 ):
            $this->produitVendu = 1;
        else:
            $this->produitVendu = 0;
        endif;        
    }
    
    function archiver() {
        $this->setProduitArchive(1);
    }
    
    function getProduitId() {
        return $this->produitId;
    }

    function getProduitPdvId() {
        return $this->produitPdvId;
    }

    function getProduitRefUsine() {
        return $this->produitRefUsine;
    }

    function getProduitDesignation() {
        return $this->produitDesignation;
    }

    function getProduitUsineId() {
        return $this->produitUsineId;
    }

    function getProduitUsine() {
        return $this->produitUsine;
    }

    function getProduitFamilleId() {
        return $this->produitFamilleId;
    }

    function getProduitFamille() {
        return $this->produitFamille;
    }

    function getProduitUniteId() {
        return $this->produitUniteId;
    }

    function getProduitUnite() {
        return $this->produitUnite;
    }

    function getProduitMultiple() {
        return $this->produitMultiple;
    }

    function getProduitPrixAchatUnitaire() {
        return $this->produitPrixAchatUnitaire;
    }

    function getProduitPrixAchatPalette() {
        return $this->produitPrixAchatPalette;
    }

    function getProduitSeuilPalette() {
        return $this->produitSeuilPalette;
    }

    function getProduitPrixVente() {
        return $this->produitPrixVente;
    }

    function getProduitPoids() {
        return $this->produitPoids;
    }

    function getProduitGestionStock() {
        return $this->produitGestionStock;
    }

    function getProduitGestionBain() {
        return $this->produitGestionBain;
    }

    function getProduitTVA() {
        return $this->produitTVA;
    }

    function setProduitId($produitId) {
        $this->produitId = $produitId;
    }

    function setProduitPdvId($produitPdvId) {
        $this->produitPdvId = $produitPdvId;
    }

    function setProduitRefUsine($produitRefUsine) {
        $this->produitRefUsine = strtoupper($produitRefUsine);
    }

    function setProduitDesignation($produitDesignation) {
        $this->produitDesignation = $produitDesignation;
    }

    function setProduitUsineId($produitUsineId) {
        $this->produitUsineId = $produitUsineId;
    }

    function setProduitUsine($produitUsine) {
        $this->produitUsine = $produitUsine;
    }

    function setProduitFamilleId($produitFamilleId) {
        $this->produitFamilleId = $produitFamilleId;
    }

    function setProduitFamille($produitFamille) {
        $this->produitFamille = $produitFamille;
    }

    function setProduitUniteId($produitUniteId) {
        $this->produitUniteId = $produitUniteId;
    }

    function setProduitUnite($produitUnite) {
        $this->produitUnite = $produitUnite;
    }

    function setProduitMultiple($produitMultiple) {
        $this->produitMultiple = $produitMultiple;
    }

    function setProduitPrixAchatUnitaire($produitPrixAchatUnitaire) {
        $this->produitPrixAchatUnitaire = $produitPrixAchatUnitaire;
    }

    function setProduitPrixAchatPalette($produitPrixAchatPalette) {
        $this->produitPrixAchatPalette = $produitPrixAchatPalette;
    }

    function setProduitSeuilPalette($produitSeuilPalette) {
        $this->produitSeuilPalette = $produitSeuilPalette;
    }

    function setProduitPrixVente($produitPrixVente) {
        $this->produitPrixVente = $produitPrixVente;
    }

    function setProduitPoids($produitPoids) {
        $this->produitPoids = $produitPoids;
    }

    function setProduitGestionStock($produitGestionStock) {
        $this->produitGestionStock = $produitGestionStock;
    }

    function setProduitGestionBain($produitGestionBain) {
        $this->produitGestionBain = $produitGestionBain;
    }

    function setProduitTVA($produitTVA) {
        $this->produitTVA = $produitTVA;
    }    
    function getProduitEAN() {
        return $this->produitEAN;
    }

    function setProduitEAN($produitEAN) {
        $this->produitEAN = $produitEAN;
    }
    function getProduitEncours() {
        return $this->produitEncours;
    }

    function getProduitDispo() {
        return $this->produitDispo;
    }

    function setProduitEncours($produitEncours) {
        $this->produitEncours = $produitEncours;
    }

    function setProduitDispo($produitDispo) {
        $this->produitDispo = $produitDispo;
    }
    function getProduitStocks() {
        return $this->produitStocks;
    }

    function setProduitStocks($produitStocks) {
        $this->produitStocks = $produitStocks;
    }
    function getProduitStock() {
        return $this->produitStock;
    }

    function setProduitStock($produitStock) {
        $this->produitStock = $produitStock;
    }
    function getProduitEnCommande() {
        return $this->produitEnCommande;
    }

    function setProduitEnCommande($produitEnCommande) {
        $this->produitEnCommande = $produitEnCommande;
    }
    function getProduitArchive() {
        return $this->produitArchive;
    }

    function setProduitArchive($produitArchive) {
        $this->produitArchive = $produitArchive;
    }
    function getProduitVendu() {
        return $this->produitVendu;
    }

    function setProduitVendu($produitVendu) {
        $this->produitVendu = $produitVendu;
    }
}
