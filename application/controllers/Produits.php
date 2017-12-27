<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Produits extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->view_folder = strtolower(__CLASS__) . '/';
        /* Connexion */
        if (!$this->ion_auth->logged_in() || !$this->session->userdata('loggedPdvId')) :
            redirect('secure/login');
        endif;
    }

    /**
     * Retourne la liste de sproduits exploitée en JS par Bootstrap-table
     */
    public function getAllProduits() {
        echo json_encode($this->managerProduits->listeAll(array(), 'produitNom ASC', 'array'));
    }

    /**
     * Recherche des produits lors de la saisie dans la création des devis et BDC
     */
    public function produitSearch() {
        $this->form_validation->set_rules('produitSearch', 'Recherche', 'required|trim');
        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            $produits = $this->managerProduits->liste(array('produitDesignation LIKE' => '%' . $this->input->post('produitSearch') . '%'), 'produitDesignation ASC', 'object');
            if (!empty($produits)) :
                foreach ($produits as $p) :
                    $data[] = array(
                        'produitId' => $p->getProduitId(),
                        'produitDesignation' => $p->getProduitDesignation(),
                        'produitUsine' => $p->getProduitUsine()->getUsineNom(),
                        'produitFamille' => $p->getProduitFamille()->getFamilleNom(),
                        'produitPrixVente' => $p->getProduitPrixVente(),
                        'produitMultiple' => $p->getProduitMultiple(),
                        'produitUniteId' => $p->getProduitUniteId(),
                        'produitDispo' => $p->getProduitDispo()
                    );
                endforeach;
            else :
                $data = array();
            endif;
            echo json_encode(array('type' => 'success', 'produits' => $data));
            exit;
        endif;
    }

    public function index() {

        $data = array(
            'title' => 'Listing des produits',
            'familles' => $this->managerFamilles->liste(),
            'usines' => $this->managerUsines->liste(),
            'description' => '',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function ficheProduit($produitId = null) {
        if (!$produitId) :
            redirect('produits/');
            exit;
        endif;

        $produit = $this->managerProduits->getProduitById(intval($produitId));

        if (empty($produit)) :
            redirect('produits');
            exit;
        else :

            $ventes = $this->managerBdcarticles->getArticlesByProduitId($produit->getProduitId());
            if (!empty($ventes)):
                foreach ($ventes as $v):
                    $v->hydrateBdc();
                endforeach;
            endif;


            $data = array(
                'produit' => $produit,
                'ventes' => $ventes,
                /*
                 *  'appros' => $this->m_commandearticle->commandeArticlesProduit($produitId),
                 *  'ventes' => $this->m_bdcarticle->analyseVenteArticle(intval($produitId)),
                 */
                'familles' => $this->managerFamilles->liste(),
                'usines' => $this->managerUsines->liste(),
                'title' => 'Fiche produit ' . $produit->getProduitDesignation(),
                'description' => '',
                'keywords' => '',
                'content' => $this->view_folder . __FUNCTION__
            );
            $this->load->view('template/content', $data);
        endif;
    }

    public function addProduit() {

        $this->form_validation->set_rules('addProduitId', 'Id', 'is_natural_no_zero|trim');
        $this->form_validation->set_rules('addProduitDesignation', 'Désignation du produit', 'required|trim');
        $this->form_validation->set_rules('addProduitUsine', 'Usine Id', 'is_natural_no_zero|required|trim');
        $this->form_validation->set_rules('addProduitRefUsine', 'Référence Usine', 'trim');
        $this->form_validation->set_rules('addProduitFamille', 'Famille', 'is_natural_no_zero|required|trim');
        $this->form_validation->set_rules('addProduitUnite', 'Unite', 'in_list[1,2,3]|numeric|trim');
        $this->form_validation->set_rules('addProduitMultiple', 'Multiple', 'numeric|required|trim');
        $this->form_validation->set_rules('addProduitAchatUnitaire', 'Prix achat unitaire', 'numeric|required|trim');
        $this->form_validation->set_rules('addProduitSeuilPalette', 'Quantité mini palette', 'numeric|trim');
        $this->form_validation->set_rules('addProduitAchatPalette', 'Prix achat palette', 'numeric|trim');
        $this->form_validation->set_rules('addProduitVenteUnitaire', 'Prix de vente', 'numeric|required|trim');
        $this->form_validation->set_rules('addProduitPoids', 'Poids', 'numeric|trim');
        $this->form_validation->set_rules('addProduitBain', 'Gestion des bains', 'in_list[0,1]|trim');
        $this->form_validation->set_rules('addProduitStock', 'Gestion des stocks', 'in_list[0,1]|trim');
        $this->form_validation->set_rules('addProduitEAN', 'Code EAN du produit', 'numeric|trim');
        $this->form_validation->set_rules('addProduitTVA', 'Taux de TVA du produit', 'numeric|trim');

        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            if ($this->input->post('addProduitId')) :
                $produit = $this->managerProduits->getProduitById($this->input->post('addProduitId'));
                if (empty($produit)) :
                    echo json_encode(array('type' => 'error', 'message' => 'Produit ? mais de quel produit tu parles ?'));
                    exit;
                else :
                    $produit->setProduitUsine($this->input->post('addProduitUsine'));
                    $produit->setProduitFamille($this->input->post('addProduitFamille'));
                    $produit->setProduitUnite($this->input->post('addProduitUnite'));
                    $produit->setProduitRefUsine($this->input->post('addProduitRefUsine'));
                    $produit->setProduitMultiple($this->input->post('addProduitMultiple'));
                    $produit->setProduitDesignation($this->input->post('addProduitDesignation'));
                    $produit->setProduitPrixAchatUnitaire($this->input->post('addProduitAchatUnitaire'));
                    $produit->setProduitSeuilPalette($this->input->post('addProduitSeuilPalette'));
                    $produit->setProduitPrixAchatPalette($this->input->post('addProduitAchatPalette'));
                    $produit->setProduitPrixVente($this->input->post('addProduitVenteUnitaire'));
                    $produit->setProduitPoids($this->input->post('addProduitPoids'));
                    $produit->setProduitGestionBain($this->input->post('addProduitBain'));
                    $produit->setProduitGestionStock($this->input->post('addProduitStock'));
                    $produit->setProduitEAN($this->input->post('addProduitEAN'));
                    $produit->setProduitTVA($this->input->post('addProduitTVA'));

                    $this->managerProduits->editer($produit);
                endif;
            else :
                $data = array(
                    'produitPdvId' => $this->session->userdata('loggedPdvId'),
                    'produitRefUsine' => $this->input->post('addProduitRefUsine'),
                    'produitDesignation' => $this->input->post('addProduitDesignation'),
                    'produitUsineId' => intval($this->input->post('addProduitUsine')),
                    'produitFamilleId' => intval($this->input->post('addProduitFamille')),
                    'produitUniteId' => intval($this->input->post('addProduitUnite')),
                    'produitMultiple' => $this->input->post('addProduitMultiple'),
                    'produitPrixAchatUnitaire' => $this->input->post('addProduitAchatUnitaire'),
                    'produitPrixAchatPalette' => $this->input->post('addProduitAchatPalette'),
                    'produitPrixVente' => $this->input->post('addProduitVenteUnitaire'),
                    'produitSeuilPalette' => $this->input->post('addProduitSeuilPalette'),
                    'produitPoids' => $this->input->post('addProduitPoids'),
                    'produitGestionBain' => $this->input->post('addProduitBain') ? 1 : 0,
                    'produitGestionStock' => $this->input->post('addProduitStock') ? 1 : 0,
                    'produitEAN' => $this->input->post('addProduitEAN'),
                    'produitTVA' => $this->input->post('addProduitTVA'),
                    'produitArchive' => 0
                );

                $produit = new Produit($data);
                $this->managerProduits->ajouter($produit);
            endif;

            echo json_encode(array('type' => 'success', 'produitId' => $produit->getProduitId()));
            exit;
        endif;
    }

    /**
     * Créé un produit copie du produit source
     */
    public function copyProduit() {

        $this->form_validation->set_rules('produitId', 'ID du produit source', 'required|is_natural_no_zero|trim');
        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            $produitSource = $this->managerProduits->getProduitById(intval($this->input->post('produitId')));
            if (empty($produitSource)) :
                echo json_encode(array('type' => 'error', 'message' => 'Hummm, comment copier un fantôme ?'));
                exit;
            else :
                $produitNew = $produitSource;
                $produitNew->setProduitDesignation('[COPY] ' . $produitNew->getProduitDesignation());

                $this->managerProduits->ajouter($produitNew);

                echo json_encode(array('type' => 'success', 'produitId' => $produitNew->getProduitId()));
                exit;
            endif;
        endif;
    }

    public function delProduit() {
        $this->form_validation->set_rules('produitId', 'produit', 'required|is_natural_no_zero|trim');
        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            $produit = $this->managerProduits->getProduitById(intval($this->input->post('produitId')));
            if (empty($produit)) :
                echo json_encode(array('type' => 'error', 'message' => 'Produit fantôme.'));
                exit;
            else :
                /* recherche des eventuelles ventes de ce produit */
                $ventes = $this->managerBdcarticles->liste(array('articleProduitId' => $produit->getProduitId()));
                $appros = $this->managerAppros->liste(array('approProduitId' => $produit->getProduitId()));
                if (!empty($ventes) || !empty($appros) || !empty($produit->getProduitStocks())) :
                    /* le produit est lié à des éléments commerciaux */
                    $produit->archiver();
                    $this->managerProduits->editer($produit);
                else :
                    $this->managerProduits->delete($produit);
                    unset($produit);
                endif;

                echo json_encode(array('type' => 'success'));
                exit;
            endif;
        endif;
    }

    public function getProduit() {
        $this->form_validation->set_rules('produitId', 'produit', 'required|is_natural_no_zero|trim');
        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            $produit = $this->managerProduits->getProduitById($this->input->post('produitId'), 'array');
            echo json_encode(array('type' => 'success', 'produit' => $produit));
            exit;
        endif;
    }

    /* --- FAMILLES --- */

    public function familles() {
        $data = array(
            'familles' => $this->managerFamilles->liste(),
            'title' => 'Gestion des familles de produits',
            'description' => '',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function addFamille() {
        $this->form_validation->set_rules('addFamilleId', 'Id', 'is_natural_no_zero|trim');
        $this->form_validation->set_rules('addFamilleNom', 'Famille', 'required|trim');

        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            if ($this->input->post('addFamilleId')) :
                $famille = $this->managerFamilles->getFamilleById(intval($this->input->post('addFamilleId')));
                if (empty($famille)) :
                    echo json_encode(array('type' => 'error', 'message' => 'Famille fantôme'));
                    exit;
                else :
                    $famille->setFamilleNom($this->input->post('addFamilleNom'));
                    $this->managerFamilles->editer($famille);
                endif;
            else :
                $data = array(
                    'familleNom' => $this->input->post('addFamilleNom'),
                    'famillePdvId' => $this->session->userdata('loggedPdvId')
                );
                $famille = new Famille($data);
                $this->managerFamilles->ajouter($famille);
            endif;
            echo json_encode(array('type' => 'success'));
            exit;
        endif;
    }

    public function delFamille() {
        $this->form_validation->set_rules('familleId', 'famille', 'required|numeric|trim');
        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            $famille = $this->managerFamilles->getFamilleById(intval($this->input->post('familleId')));
            if (empty($famille)) :
                echo json_encode(array('type' => 'error', 'message' => 'Famille fantôme.'));
                exit;
            else :
                $produits = $this->managerProduits->liste(array('produitFamilleId' => $famille->getFamilleId()), 'array');
                if (!empty($produits)) :
                    echo json_encode(array('type' => 'error', 'message' => 'Impossible de supprimer une famille qui appartient à des produits'));
                    exit;
                else :
                    $this->managerFamilles->delete($famille);
                    echo json_encode(array('type' => 'success'));
                    exit;
                endif;
            endif;
        endif;
    }

    public function getFamille() {
        $this->form_validation->set_rules('familleId', 'Famille', 'required|is_natural_no_zero|trim');
        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            echo json_encode(array('type' => 'success', 'famille' => $this->managerFamilles->getFamilleById(intval($this->input->post('familleId')), 'array')));
            exit;
        endif;
    }

    /* --- USINES --- */

    public function usines() {
        $data = array(
            'usines' => $this->managerUsines->liste(),
            'title' => 'Gestion des usines',
            'description' => '',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function addUsine() {

        $this->form_validation->set_rules('addUsineId', 'Id', 'is_natural_no_zero|trim');
        $this->form_validation->set_rules('addUsineNom', 'Usine', 'required|trim');
        $this->form_validation->set_rules('addUsineEmail', 'Email', 'valid_email|trim');
        $this->form_validation->set_rules('addUsineCodeClient', 'Code client', 'trim');

        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            if ($this->input->post('addUsineId')) :
                $usine = $this->managerUsines->getUsineById(intval($this->input->post('addUsineId')));
                if (empty($usine)) :
                    echo json_encode(array('type' => 'error', 'message' => 'Usine fantôme'));
                    exit;
                else :
                    $usine->setUsineNom($this->input->post('addUsineNom'));
                    $usine->setUsineEmail($this->input->post('addUsineEmail'));
                    $usine->setUsineCodeClient($this->input->post('addUsineCodeClient'));
                    $this->managerUsines->editer($usine);
                endif;
            else :
                $data = array(
                    'usineNom' => $this->input->post('addUsineNom'),
                    'usineEmail' => $this->input->post('addUsineEmail'),
                    'usineCodeClient' => $this->input->post('addUsineCodeClient'),
                    'usinePdvId' => $this->session->userdata('loggedPdvId')
                );
                $usine = new Usine($data);
                $this->managerUsines->ajouter($usine);
            endif;
            echo json_encode(array('type' => 'success'));
            exit;
        endif;
    }

    public function delUsine() {
        $this->form_validation->set_rules('usineId', 'usine', 'required|is_natural_no_zero|trim');
        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            $usine = $this->managerUsines->getUsineById(intval($this->input->post('usineId')));
            if (empty($usine)) :
                echo json_encode(array('type' => 'error', 'message' => 'Usnine fantôme.'));
                exit;
            else :
                $produits = $this->managerProduits->liste(array('produitUsineId' => $usine->getUsineId()), 'array');
                if (!empty($produits)) :
                    echo json_encode(array('type' => 'error', 'message' => 'Impossible de supprimer une usine liée à des produits'));
                    exit;
                else :
                    $this->managerUsines->delete($usine);
                    echo json_encode(array('type' => 'success'));
                    exit;
                endif;
            endif;
        endif;
    }

    public function getUsine() {
        $this->form_validation->set_rules('usineId', 'Usine', 'required|is_natural_no_zero|trim');
        if ($this->form_validation->run()) :
            echo json_encode(array('type' => 'success', 'usine' => $this->managerUsines->getUsineById($this->input->post('usineId'), 'array')));
            exit;
        else :
            log_message('error', 'Echec de récupération des informations pour une usine.');
            exit;
        endif;
    }

    public function getStock() {
        $this->form_validation->set_rules('produitId', 'Produit', 'required|numeric|trim');
        if ($this->form_validation->run()) :
            $stocks = $this->m_stock->stockProduit(intval($this->input->post('produitId')));
            $stockProduit = '';
            if (!empty($stocks)) :
                $stockProduit .= '<table class="table table-condensed table-striped"><thead><th>Qte</th><th>Bain</th><th>Calibre</th><th>PA</th></thead><tbody>';
                foreach ($stocks as $s) :
                    $stockProduit .= '<tr><td>' . $s->stockQte . '</td><td>' . $s->stockBain . '</td><td>' . $s->stockCalibre . '</td><td>' . $s->stockPrixAchat . '</td></tr>';
                endforeach;
                $stockProduit .= '</tbody></table>';
            endif;

            $bdc = $this->m_bdcarticle->bdcProduit(intval($this->input->post('produitId')));
            if (!empty($bdc)) :
                $stockProduit .= '<table class="table table-condensed table-striped"><thead><th>Bdc</th><th>Reste à livrer</th></thead><tbody>';
                foreach ($bdc as $b) :
                    $qte = $b->articleQte - $b->articleQteLivree;
                    $stockProduit .= '<tr><td>' . $b->articleBdcId . '</td><td>' . number_format($qte, 2, ',', ' ') . '</td></tr>';
                endforeach;
                $stockProduit .= '</tbody></table>';
            endif;
            $stockProduit .= '<a href="' . site_url('produits/ficheProduit/' . intval($this->input->post('produitId'))) . '" target="_blank">Voir la fiche produit</a>';
            echo json_encode(array('type' => 'success', 'stocks' => $stockProduit));
            exit;
        else :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
    }

    public function addStock() {

        $this->form_validation->set_rules('addStockProduitId', 'Produit', 'required|is_natural_no_zero|trim');
        $this->form_validation->set_rules('addStockQte', 'Quantité', 'required|numeric|trim');
        $this->form_validation->set_rules('addStockBain', 'Bain', 'trim');
        $this->form_validation->set_rules('addStockCalibre', 'Calibre', 'trim');
        $this->form_validation->set_rules('addStockPrixAchat', 'Prix achat', 'required|numeric|trim');

        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            $produit = $this->managerProduits->getProduitById(intval($this->input->post('addStockProduitId')));
            if (empty($produit)) :
                echo json_encode(array('type' => 'error', 'message' => 'Produit fantôme.'));
                exit;
            else :
                if (!empty($produit->getProduitStocks())) :
                    foreach ($produit->getProduitStocks() as $s) :
                        if ($s->getStockBain() == $this->input->post('addStockBain') && $s->getStockCalibre() == $this->input->post('addStockCalibre') && $s->getStockPrixAchat() == $this->input->post('addStockPrixAchat')) :
                            $s->setStockQte($s->getStockQte() + floatval($this->input->post('addStockQte')));
                            $this->managerStocks->editer($s);
                            echo json_encode(array('type' => 'success'));
                            exit;
                        endif;
                    endforeach;
                endif;

                $data = array(
                    'stockProduitId' => $produit->getProduitId(),
                    'stockQte' => floatval($this->input->post('addStockQte')),
                    'stockPrixAchat' => floatval($this->input->post('addStockPrixAchat')),
                    'stockBain' => $this->input->post('addStockBain') ? $this->input->post('addStockBain') : '',
                    'stockCalibre' => $this->input->post('addStockCalibre') ? $this->input->post('addStockCalibre') : '',
                    'stockEmplacement' => ''
                );
                $stock = new Stock($data);
                $this->managerStocks->ajouter($stock);

                echo json_encode(array('type' => 'success'));
                exit;
            endif;
        endif;
    }

    public function cleanStocks() {

        $produits = $this->managerProduits->liste();
        foreach ($produits as $p) :
            $p->checkVentes();
            if ($p->getProduitVendu() == 0) :
                $this->managerProduits->delete($p);
            endif;
        endforeach;
        unset($produits);
        $stocks = $this->managerStocks->liste();
        foreach ($stocks as $s) :
            $s->setStockQte(0);
            $this->managerStocks->editer($s);
        endforeach;
    }

}
