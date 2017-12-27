<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  | -------------------------------------------------------------------
  | AUTO-LOADER
  | -------------------------------------------------------------------
  | This file specifies which systems should be loaded by default.
  |
  | In order to keep the framework as light-weight as possible only the
  | absolute minimal resources are loaded by default. For example,
  | the database is not connected to automatically since no assumption
  | is made regarding whether you intend to use it.  This file lets
  | you globally define which systems you would like loaded with every
  | request.
  |
  | -------------------------------------------------------------------
  | Instructions
  | -------------------------------------------------------------------
  |
  | These are the things you can load automatically:
  |
  | 1. Packages
  | 2. Libraries
  | 3. Helper files
  | 4. Custom config files
  | 5. Language files
  | 6. Models
  |
 */

/*
  | -------------------------------------------------------------------
  |  Auto-load Packges
  | -------------------------------------------------------------------
  | Prototype:
  |
  |  $autoload['packages'] = array(APPPATH.'third_party', '/usr/local/shared');
  |
 */

$autoload['packages'] = array();


/*
  | -------------------------------------------------------------------
  |  Auto-load Libraries
  | -------------------------------------------------------------------
  | These are the classes located in the system/libraries folder
  | or in your application/libraries folder.
  |
  | Prototype:
  |
  |	$autoload['libraries'] = array('database', 'session', 'xmlrpc');
 */

$autoload['libraries'] = array('session', 'database', 'form_validation', 'cart', 'cxwork', 'email', 'ion_auth',
    'client', 'conditionreglement', 'modereglement', 'pdv', 'produit', 'bdc', 'bdcarticle', 'bdctva', 'famille', 'usine', 'stock',
    'devis', 'devisarticle', 'devistva', 'bl', 'livraison', 'bltva', 'collaborateur', 'facture', 'factureligne', 'facturetva', 'reglement',
    'avoir', 'avoirligne', 'avoirtva',
    'caisse', 'remise', 'token'
);


/*
  | -------------------------------------------------------------------
  |  Auto-load Helper Files
  | -------------------------------------------------------------------
  | Prototype:
  |
  |	$autoload['helper'] = array('url', 'file');
 */

$autoload['helper'] = array('url', 'html', 'form', 'text', 'file');


/*
  | -------------------------------------------------------------------
  |  Auto-load Config files
  | -------------------------------------------------------------------
  | Prototype:
  |
  |	$autoload['config'] = array('config1', 'config2');
  |
  | NOTE: This item is intended for use ONLY if you have created custom
  | config files.  Otherwise, leave it blank.
  |
 */

$autoload['config'] = array();


/*
  | -------------------------------------------------------------------
  |  Auto-load Language files
  | -------------------------------------------------------------------
  | Prototype:
  |
  |	$autoload['language'] = array('lang1', 'lang2');
  |
  | NOTE: Do not include the "_lang" part of your file.  For example
  | "codeigniter_lang.php" would be referenced as array('codeigniter');
  |
 */

$autoload['language'] = array();


/*
  | -------------------------------------------------------------------
  |  Auto-load Models
  | -------------------------------------------------------------------
  | Prototype:
  |
  |	$autoload['model'] = array('model1', 'model2');
  |
 */

$autoload['model'] = array(
    'model_usines' => 'managerUsines',
    'model_familles' => 'managerFamilles',
    'model_clients' => 'managerClients',
    'model_conditionsreglement' => 'managerConditionsreglement',
    'model_modesreglement' => 'managerModesreglement',
    'model_produits' => 'managerProduits',
    'model_stocks' => 'managerStocks',
    'model_bdc' => 'managerBdc',
    'model_bdcarticles' => 'managerBdcarticles',
    'model_bdctva' => 'managerBdctva',
    'model_bls' => 'managerBls',
    'model_livraisons' => 'managerLivraisons',
    'model_bltva' => 'managerBltva',
    'model_devis' => 'managerDevis',
    'model_devisarticles' => 'managerDevisarticles',
    'model_devistva' => 'managerDevistva',
    'model_pdv' => 'managerPdv',
    'model_collaborateurs' => 'managerCollaborateurs',
    'model_reglements' => 'managerReglements',
    'model_avoirs' => 'managerAvoirs',
    'model_avoirtva' => 'managerAvoirtva',
    'model_avoirlignes' => 'managerAvoirlignes',
    'model_factures' => 'managerFactures',
    'model_facturetva' => 'managerFacturetva',
    'model_facturelignes' => 'managerFacturelignes',
    'model_caisses' => 'managerCaisses',
    'model_remises' => 'managerRemises'
);


/* End of file autoload.php */
/* Location: ./application/config/autoload.php */