<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class My_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance d'un produit dans la bdd
     *
     * @param int $produitId ID du Produit
     * @return boolean TRUE si le produit existe
     */
    public function existProduit($produitId) {
        $this->form_validation->set_message('existProduit', 'Ce produit est introuvable.');
        if ($this->managerProduits->count(array('produitId' => $produitId)) == 1 || !$produitId || substr($produitId, 0, 6) == 'Unique') :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance d'un devis dans la bdd
     *
     * @param int $devisId ID du Devis
     * @return boolean TRUE si le devis existe
     */
    public function existDevis($devisId) {
        $this->form_validation->set_message('existDevis', 'Ce devis est introuvable.');
        if ($this->managerDevis->count(array('devisId' => $devisId)) == 1 || !$devisId) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance d'un stpck dans la bdd
     *
     * @param int $stockId ID du stock
     * @return boolean TRUE si le stock existe
     */
    public function existStock($stockId) {
        $this->form_validation->set_message('existStock', 'Ce stock est introuvable.');
        if ($this->managerDevis->count(array('stockId' => $stockId)) == 1) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance d'un devis dans la bdd
     *
     * @param int $devisId ID du Devis
     * @return boolean TRUE si le devis existe
     */
    public function existBdc($bdcId) {
        $this->form_validation->set_message('existBdc', 'Ce bon de commande est introuvable.');
        if ($this->managerBdc->count(array('bdcId' => $bdcId)) == 1 || !$bdcId) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance d'un Bl dans la bdd
     *
     * @param int $blId ID du Bl
     * @return boolean TRUE si le Bl existe
     */
    public function existBl($blId) {
        $this->form_validation->set_message('existBl', 'Ce bon de livraison est introuvable.');
        if ($this->managerBls->count(array('blId' => $blId)) == 1) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance d'une facture dans la bdd
     *
     * @param int $factureId ID de la facture
     * @return boolean TRUE si la facture existe
     */
    public function existFacture($factureId) {
        $this->form_validation->set_message('existFacture', 'Cette facture est introuvable.');
        if ($this->managerFactures->count(array('factureId' => $factureId)) == 1 || !$factureId) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance d'un avoir dans la bdd
     *
     * @param int $avoirId ID de l'avoir
     * @return boolean TRUE si l'avoir existe
     */
    public function existAvoir($avoirId) {
        $this->form_validation->set_message('existAvoir', 'Cet avoir est introuvable.');
        if ($this->managerAvoirs->count(array('avoirId' => $avoirId)) == 1) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Fonction pour from_validation qui vérifie l'existance d'un réglement dans la bdd
     *
     * @param int $reglementId ID du réglement
     * @return boolean TRUE si le réglement existe
     */
    public function existReglement($reglementId) {
        $this->form_validation->set_message('existReglement', 'Ce réglement est introuvable.');
        if ($this->managerReglements->count(array('reglementId' => $reglementId)) == 1 || !$reglementId) :
            return true;
        else :
            return false;
        endif;
    }

    /**
     * Retourne le montant TTC et les montants des différents taux de TVA pour le panier
     */
    protected function getTotauxTaxes() {

        if ($this->session->userdata('venteClientId')) :
            $client = $this->managerClients->getClientById($this->session->userdata('venteClientId'));
        else :
            $client = false;
        endif;

        if ($this->cart->contents()) :
            if ($client && $client->getClientExonerationTVA() == 1) :
                $this->session->set_userdata('venteTTC', $this->cart->total());
                $this->session->set_userdata('venteTVA', array('0' => $client->getClientIntracom()));
            else :
                $TTC = 0;
                $taxableHT = $TVA = array();
                foreach ($this->cart->contents() as $item) :
                    $montantHTItem = round($item['price'] * $item['qty'], 2);

                    if (isset($taxableHT[$item['options']['tauxTVA']])) :
                        $taxableHT[$item['options']['tauxTVA']] += $montantHTItem;
                    else :
                        $taxableHT[$item['options']['tauxTVA']] = $montantHTItem;
                    endif;

                endforeach;

                foreach ($taxableHT as $taux => $montant):
                    $tva = round($montant * $taux / 100, 2);
                    $TVA[$taux] = $tva;
                    $TTC += $tva + $montant;
                endforeach;

                $this->session->set_userdata('venteTTC', $TTC);
                $this->session->set_userdata('venteTVA', $TVA);
            endif;
            return true;
        else :
            return false;
        endif;
    }

    public function initSession() {
        $this->venteInit();
    }

    protected function venteInit() {
        $this->cart->destroy();
        $variables = array(
            'venteId',
            'venteDate',
            'venteType',
            'venteClientId',
            'venteClientType',
            'venteAcompte',
            'venteDevisId',
            'venteBdcId',
            'venteCommentaire',
            'ventePoids',
            'venteEditable',
            'venteCollaborateurId',
            'venteCollaborateur',
            'venteTTC',
            'venteTVA',
            'venteExonerationTVA',
            'venteEtat '
        );
        $this->session->unset_userdata($variables);
    }

    protected function ligneMarge($produitId, $qte, $prixVente, $remise) {
        $produit = $this->managerProduits->getProduitById(intval($produitId));
        if ($produit) :
            if ($qte >= $produit->getProduitSeuilPalette() && $produit->getProduitSeuilPalette() > 0) :
                $prixAchat = $produit->getProduitPrixAchatPalette();
            else :
                $prixAchat = $produit->getProduitPrixAchatUnitaire();
            endif;
            return ceil(((($prixVente * (100 - $remise) / 100) / $prixAchat) - 1) * 100);
            exit;
        endif;
    }

    protected function getPoidsCart() {
        if ($this->cart->contents()) :
            $poids = 0;
            foreach ($this->cart->contents() as $item) :
                if (substr($item['id'], 0, 6) != 'Unique') :
                    $produit = $this->managerProduits->getProduitById($item['id']);
                    $poids += $produit->getProduitPoids() * $item['qty'];
                endif;
            endforeach;
            $this->session->set_userdata('ventePoids', $poids);
        endif;
    }

    public function venteDateChange() {
        $this->form_validation->set_rules('venteDate', 'Date de la vente', 'required|trim');
        if ($this->form_validation->run()) :
            $temp = explode('-', $this->input->post('venteDate'));
            $this->session->set_userdata('venteDate', mktime(1, 0, 0, $temp[1], $temp[2], $temp[0]));
            echo json_encode(array('type' => 'success'));
            exit;
        else :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
    }

    public function venteCollaborateurChange() {
        $this->form_validation->set_rules('venteCollaborateurId', 'Servi par', 'numeric|required|trim');
        if ($this->form_validation->run()) :
            $this->session->set_userdata('venteCollaborateurId', $this->input->post('venteCollaborateurId'));
            echo json_encode(array('type' => 'success'));
            exit;
        else :
            log_message('error', __CLASS__ . '/' . __FUNCTION__ . ' : ' . 'Erreur lors de la selection du collaborateur');
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        endif;
    }

    /**
     * Change le client d'un devis
     */
    public function venteClientChange() {

        $this->form_validation->set_rules('clientId', 'client', 'required|is_natural_no_zero|trim');
        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            $client = $this->managerClients->getClientById(intval($this->input->post('clientId')), 'array');
            if (empty($client)) :
                echo json_encode(array('type' => 'error', 'message' => 'Client en cavale !'));
                exit;
            else :
                $this->session->set_userdata(array('venteClientId' => $client['clientId'], 'venteClientType' => $client['clientType'], 'venteExonerationTVA' => $client['clientExonerationTVA']));
                $this->getTotauxTaxes();
                echo json_encode(array('type' => 'success', 'client' => $client));
                exit;
            endif;
        endif;
    }

    public function getCartArticle() {

        if ($this->form_validation->run('getRowArticle') && $this->cart->contents()) :

            $cartInfos = $this->cart->get_item($this->input->post('rowid'));
            if (substr($cartInfos['id'], 0, 6) == 'Unique') {
                $cartInfos['multiple'] = 0;
            } else { /* Multiple correspond au multiple de vente par exemple 1.08m² pour une botte de carrelage */
                $cartInfos['multiple'] = $this->managerProduits->getProduitById($cartInfos['id'])->getProduitMultiple();
            }

            echo json_encode(array('type' => 'success', 'cart' => $cartInfos));
            exit;
        endif;
    }

    public function delCartArticle() {

        if ($this->form_validation->run('getRowArticle')) :

            $this->cart->remove($this->input->post('rowid'));
            $this->getPoidsCart();
            $this->getTotauxTaxes();

            echo json_encode(array('type' => 'success'));
        else :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
        endif;
        exit;
    }

    protected function ajouteArticleAuPanier($rowId, $idProduit, $qte, $prixNet, $designation, $remise, $options) {

        $data = array(
            'id' => $idProduit,
            'qty' => $qte,
            'price' => $prixNet,
            'name' => $designation,
            'remise' => $remise,
            'options' => $options
        );

        if ($rowId) :
            $data['rowid'] = $rowId;
            $this->cart->update($data);
        else :
            $this->cart->insert($data);
        endif;
    }

}
