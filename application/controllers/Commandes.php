<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Commandes extends My_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Europe/Paris');
        $this->view_folder = strtolower(__CLASS__) . '/';
        /* Connexion */
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin() || !$this->session->userdata('loggedPdvId')) :
            redirect('secure/login');
        endif;
    }

    public function index() {
        $data = array(
            //'clients' => $this->managerClients->liste(),
            'usines' => $this->managerUsines->liste(),
            //'commandesappro' => $this->managerCommandearticles->liste( array('a.approCommandeId' => 0) ),
            'bdcarticles' => $this->managerBdcarticles->liste(array('b.bdcEtat' => 0, 'a.articleApproId !=' => 0, 'a.articleApproId !=' => null)),
            'title' => 'CX - Gestion des commandes fournisseurs',
            'description' => '',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    function qteMultiple($produitId, $qte) {
        $multiple = $this->m_produit->getOne($produitId)->produitMultiple;
        return round((ceil($qte / $multiple) * $multiple), 2);
    }

    public function enCours() {

        $commandes = $this->m_commande->liste(array('commandeEtat <' => 3));

        $data = array(
            'clients' => $this->m_client->liste(),
            'usines' => $this->m_usine->liste(),
            /* 'appros' => $this->m_commandearticle->liste(array('a.approCommandeId >'=>0,'a.approCommandeId <'=>999999999)), */
            'commandes' => $commandes, /* uniquement les commandes à recevoir */
            /* 'bdcarticles' => $this->m_bdcarticle->liste(array('b.bdcEtat'=>0,'a.articleApproId !='=>0, 'a.articleApproId !='=>999999999)), */
            'title' => 'CX - Gestion des commandes fournisseurs',
            'description' => '',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function commandesRecues() {

        $commandes = $this->m_commande->liste(array('commandeEtat' => 3));

        $data = array(
            'clients' => $this->m_client->liste(),
            'usines' => $this->m_usine->liste(),
            /* 'appros' => $this->m_commandearticle->liste(array('a.approCommandeId >'=>0,'a.approCommandeId <'=>999999999)), */
            'commandes' => $commandes, /* uniquement les commandes à recevoir */
            /* 'bdcarticles' => $this->m_bdcarticle->liste(array('b.bdcEtat'=>0,'a.articleApproId !='=>0, 'a.articleApproId !='=>999999999)), */
            'title' => 'CX - Gestion des commandes fournisseurs',
            'description' => '',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function ficheCommande($commandeId = null) {
        if (!$commandeId) :
            redirect('commandes/');
            exit;
        endif;
        $data = array(
            'commande' => $this->m_commande->getOne($commandeId),
            'appros' => $this->m_commandearticle->commandeArticles($commandeId),
            'receptions' => $this->m_commandereception->commandeReceptions($commandeId),
            'bdc' => $this->m_bdc->bdcCommande($commandeId),
            'title' => 'CX - Gestion des commandes fournisseurs',
            'description' => '',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }

    public function addApproDirect() {
        /* ajoute une ligne d'Appro depuis une fiche produit */
        $this->form_validation->set_rules('addApproQte', 'Quantité', 'numeric|required|trim|xss_clean|encode_php_tags');
        $this->form_validation->set_rules('addApproProduitId', 'ProduitId', 'numeric|trim|required|xss_clean|encode_php_tags');
        if ($this->form_validation->run()) :
            $produitId = intval($this->input->post('addApproProduitId'));
            /* on recherche une eventuelle appro en cours pour ce produit */
            if ($this->m_commandearticle->liste(array('approProduitId' => $produitId, 'approCommandeId' => 0))) :
                $currentAppro = $this->m_commandearticle->liste(array('approProduitId' => $produitId, 'approCommandeId' => 0))[0];
                $this->m_commandearticle->editer(array('approId' => $currentAppro->approId), array('approQte' => $this->qteMultiple($produitId, ($this->input->post('addApproQte') + $currentAppro->approQte))));
            else :
                $appro = array(
                    'approProduitId' => $produitId,
                    'approDate' => time(),
                    'approQte' => $this->qteMultiple($this->input->post('addApproQte'))
                );
                $this->m_commandearticle->ajouter($appro);
            endif;
            echo json_encode(array('type' => 'success'));
            exit;
        else :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' : ' . 'Impossible d\'ajouter cette ligne directe d\'approvisionnnement' . validation_errors());
            exit;
        endif;
    }

    public function addApproBdc() {
        /* Ajoute les articles de bdc selectionnés aux commandes usine */
        $this->form_validation->set_rules('articles', 'Articles a commander', 'required');
        if ($this->form_validation->run()) :
            /* $commande est un tableau qui contient les produits et leurs quantité à commander */
            $commandes = array();
            foreach ($this->input->post('articles') as $a) :
                $article = $this->m_bdcarticle->getOne($a);
                if (empty($commandes[$article->articleProduitId])) {
                    $commandes[$article->articleProduitId] = $article->articleQte;
                } else {
                    $commandes[$article->articleProduitId] += $article->articleQte;
                }
            endforeach;
            /* on va générer les commandes usine */
            foreach ($commandes as $produitId => $qte) :
                /* on recherche une commande en cours pour ce meme produit */
                if ($this->m_commandearticle->liste(array('approProduitId' => $produitId, 'approCommandeId' => 0))) :
                    $currentAppro = $this->m_commandearticle->liste(array('approProduitId' => $produitId, 'approCommandeId' => 0))[0];
                    $this->m_commandearticle->editer(array('approId' => $currentAppro->approId), array('approQte' => ($currentAppro->approQte + $qte)));
                    $approId = $currentAppro->approId;
                else :
                    $appro = array(
                        'approProduitId' => $produitId,
                        'approDate' => time(),
                        'approQte' => $qte
                    );
                    $this->m_commandearticle->ajouter($appro);
                    $approId = $this->m_commandearticle->getLast()->approId;
                endif;
                /* on renseigne dans les articles de bdc consernés l'id de la ligne d'appro */
                foreach ($this->input->post('articles') as $a) :
                    $article = $this->m_bdcarticle->getOne($a);
                    if ($article->articleProduitId == $produitId) :
                        $this->m_bdcarticle->editer(array('articleId' => $article->articleId), array('articleApproId' => $approId));
                    endif;
                endforeach;
            endforeach;
            echo json_encode(array('type' => 'success'));
            exit;
        else :
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' : ' . 'Impossible de ventiller les commandes usines des bdc sélectionnés');
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
    }

    public function addManuelBdc() {
        /* passe les articles des bdc selectionné en gestion manuelle -> articleApproId => 999999999 */
        $this->form_validation->set_rules('articles', 'Articles a passer en manuel', 'required');
        if ($this->form_validation->run()) :
            foreach ($this->input->post('articles') as $a) :
                $this->m_bdcarticle->editer(array('articleId' => $a), array('articleApproId' => 999999999));
            endforeach;
            echo json_encode(array('type' => 'success'));
            exit;
        else :
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' : ' . 'Impossible de générer la gestion manuelle des articles selectionnés');
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
    }

    public function addStockBdc() {
        /* passe les articles des bdc selectionné en vente suyr stock -> articleApproId => 10000000000 */
        $this->form_validation->set_rules('articles', 'Articles a passer en manuel', 'required');
        if ($this->form_validation->run()) :
            foreach ($this->input->post('articles') as $a) :
                $this->m_bdcarticle->editer(array('articleId' => $a), array('articleApproId' => 1000000000));
            endforeach;
            echo json_encode(array('type' => 'success'));
            exit;
        else :
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' : ' . 'Impossible de générer la vente sur stock des articles selectionnés');
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
    }

    public function modApproQte() {
        $this->form_validation->set_rules('qte', 'Quantité', 'required|trim|numeric|xss_clean|encode_php_tags');
        $this->form_validation->set_rules('approId', 'Appro id', 'required|trim|numeric|xss_clean|encode_php_tags');
        if ($this->form_validation->run()) :
            if ($this->input->post('qte') == 0) : /* on supprime l'appro */
                $this->m_bdcarticle->editer(array('articleApproId' => $this->input->post('approId')), array('articleApproId' => 0));
                $this->m_commandearticle->delete(intval($this->input->post('approId')));
            else :
                $this->m_commandearticle->editer(array('approId' => intval($this->input->post('approId'))), array('approQte' => $this->qteMultiple($this->m_commandearticle->getOne($this->input->post('approId'))->approProduitId, $this->input->post('qte'))));
            endif;
            echo json_encode(array('type' => 'success'));
            exit;
        else :
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' : ' . 'Erreur lord de la modification de la quantité d\'un appro');
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
    }

    function redactionCommande($usineId = null) {
        if ($usineId && intval($usineId) > 0) :
            $appro = $this->m_commandearticle->liste(array('p.produitUsineId' => intval($usineId), 'a.approCommandeId' => 0));
            $message = '<!DOCTYPE html><html lang="fr"><head><meta charset="utf-8"><title>Bienvenue sur Organibat.com</title>'
                    . '</head><body>'
                    . '<p><strong>Code client : ' . $appro[0]->usineCodeClient . '</strong></p>'
                    . '<p>Bonjour,</p><p>Veuillez trouver ci-dessous notre commande :</p>'
                    . '<table cellspacing="5" cellmargin="5">'
                    . '<thead><th>Your Ref</th><th>Name</th><th>Qty</th><th>Unity</th><th>Our Ref</th></thead>';
            foreach ($appro as $a) :
                $message .= '<tr><td>' . $a->produitRefUsine . '</td><td>' . $a->produitDesignation . '</td><td>' . $a->approQte . '</td><td>' . $this->cxwork->affUnite($a->produitUniteId) . '</td><td>' . '*Our Ref*' . '</td></tr>';
            endforeach;
            $message .= '</table>'
                    . '<p>Merci de nous confimer la bonne reception.<br/>Cordialement.</p>'
                    . '<p>Carreaux Import Négoce<br/>'
                    . 'Aux carreaux de Max</br/>'
                    . '59360 Le Cateau Cambresis<br/>'
                    . 'FRANCE</p>'
                    . '</body></html>';
        else :
            $message = '';
        endif;
        return $message;
    }

    public function visualisationCommande($usineId = null) {
        $usine = $this->m_usine->getOne(intval($usineId));
        if (!empty($usine)) {
            echo json_encode(array('type' => 'success', 'message' => $this->redactionCommande($usineId), 'email' => $usine->usineEmail));
        } else {
            echo json_encode(array('type' => 'error', 'message' => 'Usine invalide'));
        }
        exit;
    }

    public function addCommande() {
        /* Ajoute une commande usine pour les appro en cours */
        $this->form_validation->set_rules('usineId', 'Usine', 'required|numeric|trim');
        $usine = $this->m_usine->getOne(intval($this->input->post('usineId')));
        if ($this->form_validation->run() && !empty($usine)) :
            $appros = $this->m_commandearticle->liste(array('usineId' => $usine->usineId, 'approCommandeId' => 0));
            if ($appros) :
                /* recup de la commande redigée */
                $redaction = $this->redactionCommande(intval($this->input->post('usineId')));

                $dataCommande = array(
                    'commandePdvId' => $this->session->userdata('loggedPdvId'),
                    'commandeUsineId' => intval($this->input->post('usineId')),
                    'commandeDate' => time()
                );
                $this->m_commande->ajouter($dataCommande);
                $commandeId = $this->m_commande->getLast()->commandeId;
                /* on ajoute la référence de la commande aux appros en attente */
                foreach ($appros as $a) :
                    $this->m_commandearticle->editer(array('approId' => $a->approId), array('approCommandeId' => $commandeId));
                endforeach;

                echo json_encode(array('type' => 'success'));
                exit;
            else :
                log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' : ' . 'Impossible de créer une commande usine car aucun appro n\'est en attente');
                echo json_encode(array('type' => 'error', 'message' => 'Aucun appro en attente pour cette usine'));
                exit;
            endif;
        else :
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' : ' . 'Impossible de générer une commande fournisseur : ' . validation_errors());
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
    }

    function prixAchat($produitId, $qte) {
        $produit = $this->m_produit->getOne($produitId);
        if ($qte < $produit->produitSeuilPalette) {
            return( $produit->produitPrixAchatUnitaire );
        } else {
            return( $produit->produitPrixAchatPalette );
        }
        exit;
    }

    function commandeMajEtat($commandeId) {
        $encours = false;
        $full = true;
        $qteCommande = 0;
        $qteRecu = 0;
        $appros = $this->m_commandearticle->commandeArticles($commandeId);
        if ($appros) :
            foreach ($appros as $a) :
                $qteCommande += $a->approQte;
                $qteRecu += $a->approQteRecu;
                if ($a->approQteRecu > 0) {
                    $encours = true;
                }
                if ($a->approQteRecu < $a->approQte) {
                    $full = false;
                }
            endforeach;
        endif;
        if ($full == true) {
            $etat = 3;
        } elseif ($encours == true) {
            $etat = 2;
        } else {
            $etat = 0;
        }
        $avancement = round(($qteRecu / $qteCommande) * 100);
        if ($avancement > 100) {
            $avancement = 100;
        }
        $this->m_commande->editer(array('commandeId' => $commandeId), array('commandeEtat' => $etat, 'commandeAvancement' => $avancement));
    }

    public function receptionAppro() {
        $this->form_validation->set_rules('receptionApproId', 'ApproId', 'required|trim');
        $this->form_validation->set_rules('receptionQte', 'Quantité', 'required|numeric|trim');
        $this->form_validation->set_rules('receptionBain', 'Bain', 'trim');
        $this->form_validation->set_rules('receptionCalibre', 'Calibre', 'trim');
        if ($this->form_validation->run() && $this->input->post('receptionQte') > 0) :
            $appro = $this->m_commandearticle->getOne(intval($this->input->post('receptionApproId')));
            $PA = $this->prixAchat($appro->approProduitId, $appro->approQte);
            if ($appro) :
                /* on ajoute la qte reçue à l'avancement de l'appro */
                $this->m_commandearticle->editer(array('approId' => intval($this->input->post('receptionApproId'))), array('approQteRecu' => ($appro->approQteRecu + $this->input->post('receptionQte'))));
                /* on ajoute la reception dans la bdd */
                $dataReception = array(
                    'receptionDate' => time(),
                    'receptionApproId' => $appro->approId,
                    'receptionQte' => $this->input->post('receptionQte'),
                    'receptionBain' => $this->input->post('receptionBain'),
                    'receptionCalibre' => $this->input->post('receptionCalibre')
                );
                $this->m_commandereception->ajouter($dataReception);
                /* on gere l'entrée en stock */
                $stocks = $this->m_stock->stockProduit($appro->approProduitId); /* tous les stocks de ce produit */
                $dataStock = array(
                    'stockProduitId' => $appro->approProduitId,
                    'stockQte' => $this->input->post('receptionQte'),
                    'stockBain' => $this->input->post('receptionBain'),
                    'stockCalibre' => $this->input->post('receptionCalibre'),
                    'stockPrixAchat' => $PA,
                    'stockEmplacement' => ''
                );
                if (!empty($stocks)) :
                    $new = true;
                    foreach ($stocks as $s) :
                        if ($s->stockBain == $this->input->post('receptionBain') && $s->stockCalibre == $this->input->post('receptionCalibre') && $s->stockPrixAchat == $PA) :
                            $new = false;
                            $stockId = $s->stockId;
                            $stockQte = $s->stockQte;
                            break;
                        endif;
                    endforeach;
                    if ($new) {
                        $this->m_stock->ajouter($dataStock);
                    } else {
                        $this->m_stock->editer(array('stockId' => $stockId), array('stockQte' => ($stockQte + $this->input->post('receptionQte'))));
                    }
                else :
                    $this->m_stock->ajouter($dataStock);
                endif;
                /* on met a jour l'etat de la commande usine en reprenant la global */
                $this->commandeMajEtat($appro->approCommandeId);

                echo json_encode(array('type' => 'success'));
                exit;
            else :
                log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' : ' . 'Reception d\'une appro inexistante.');
                exit;
            endif;
        else :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
    }

    public function bdcFournisseur($commandeId = null) {
        if (!$commandeId) :
            redirect('commandes/');
            exit;
        endif;
        $commande = $this->m_commande->getOne($commandeId);
        $data = array(
            'commande' => $commande,
            'appros' => $this->m_commandearticle->commandeArticles($commande->commandeId),
            'title' => 'CX - Génération Bon de Commande Fournisseur',
            'description' => 'Génération d\'un bon de commande PDF pour une commande fournisseur',
            'keywords' => '',
            'content' => $this->view_folder . __FUNCTION__
        );
        $this->load->view('template/content_only', $data);

        // Include the main TCPDF library (search for installation path).
        require_once('application/libraries/tcpdf/tcpdf.php');
        // Extend the TCPDF class to create custom Header and Footer
        $html = $this->output->get_output();

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Xanthellis');
        $pdf->SetTitle('Bon de commande fournisseur ' . $commande->commandeId);
        $pdf->SetSubject('Commande ' . $commande->commandeId);
        $pdf->SetKeywords('CX, Commande Fournisseur');

        $pdf->SetMargins(5, 5, 5);
        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->AddPage();

        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf->Output('bonDeCommandeFournisseur' . $commande->commandeId . '.pdf', 'FI');
    }

}
