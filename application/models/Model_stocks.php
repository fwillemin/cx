<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_stocks extends CI_Model {

    protected $table = 'stocks';

    /**
     * Ajout d'un objet de la classe Stock à la BDD
     * @param Stock $stock Objet de la classe Stock
     */
    public function ajouter(Stock $stock) {
        $this->db
                ->set('stockProduitId', $stock->getStockProduitId())
                ->set('stockQte', $stock->getStockQte())
                ->set('stockBain', $stock->getStockBain())
                ->set('stockCalibre', $stock->getStockCalibre())
                ->set('stockPrixAchat', $stock->getStockPrixAchat())
                ->set('stockEmplacement', $stock->getStockEmplacement())
                ->insert($this->table);
        $stock->setStockId($this->db->insert_id());
    }

    public function editer(Stock $stock) {
        $this->db
                ->set('stockProduitId', $stock->getStockProduitId())
                ->set('stockQte', $stock->getStockQte())
                ->set('stockBain', $stock->getStockBain())
                ->set('stockCalibre', $stock->getStockCalibre())
                ->set('stockPrixAchat', $stock->getStockPrixAchat())
                ->set('stockEmplacement', $stock->getStockEmplacement())
                ->where('stockId', $stock->getStockId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe stock
     *
     * @param Stock Objet de la classe Stock
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Stock $stock) {
        $this->db->where('stockId', $stock->getStockId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function getStockById($stockId) {
        $query = $this->db->select('s.*')
                ->from('stocks s')
                ->where(array('s.stockId' => intval($stockId)))
                ->get();
        if ($query->num_rows() > 0):
            $stock = new Stock((array) $query->row());
            return $stock;
        else:
            return FALSE;
        endif;
    }

    /**
     *  retourne toutes les lignes de stock de ce produit
     */
    public function getStocksByProduitId($produitId, $retour = 'object') {
        $query = $this->db->select('s.*, p.produitUniteId AS unite')
                ->from($this->table . ' s')
                ->join('produits p', 'p.produitId = s.stockProduitId', 'left')
                ->where('p.produitPdvId', $this->session->userdata('loggedPdvId'))
                ->where(array('s.stockProduitId' => $produitId, 's.stockQte >' => 0))
                ->order_by('s.stockId', 'ASC')
                ->get();
        if ($query->num_rows() > 0):
            foreach ($query->result() AS $row):
                if ($retour == 'object'):
                    $stocks[] = new Stock((array) $row);
                else:
                    $stocks[] = (array) $row;
                endif;
            endforeach;
            return $stocks;
        else:
            return FALSE;
        endif;
    }

    /* retourne la valeur de stock de ce produit */

    public function stockTotalProduit($produitId) {
        $query = $this->db->select('SUM(stockQte) as stock')
                ->from($this->table . ' s')
                ->join('produits p', 'p.produitId = s.stockProduitId', 'left')
                ->where('p.produitPdvId', $this->session->userdata('loggedPdvId'))
                ->where('s.stockProduitId', $produitId)
                ->group_by('s.stockProduitId')
                ->get();
        if ($query->num_rows() > 0):
            foreach ($query->result() as $item):
                return $item->stock;
                break;
            endforeach;
        else:
            return 0;
        endif;
    }

    /**
     *  retourne les stocks pour une liste de produits $produitsArray = array()
     */
    public function stockArticlesBdc($produitsArray = array(), $type = 'object') {
        if (empty($produitsArray)):
            return FALSE;
        else:
            $query = $this->db->select('s.*, p.*')
                    ->from($this->table . ' s')
                    ->join('produits p', 'p.produitId = s.stockProduitId', 'left')
                    ->where('p.produitPdvId', $this->session->userdata('loggedPdvId'))
                    ->where_in('p.produitId', $produitsArray)
                    ->where(' s.stockQte > ', 0)
                    ->order_by('s.stockId', 'ASC')
                    ->get();

            if ($query->num_rows() > 0):
                foreach ($query->result() AS $row):
                    if ($type == 'object'):
                        $stocks[] = new Stock((array) $row);
                    else:
                        $stocks[] = (array) $row;
                    endif;
                endforeach;
                return $stocks;
            else:
                return FALSE;
            endif;
        endif;
    }

    public function liste($where = array(), $type = 'object') {
        $query = $this->db->select('s.*')
                ->from('stocks s')
                ->where($where)
                ->get();
        if ($query->num_rows() > 0):
            foreach ($query->result() AS $row):
                if ($type == 'object'):
                    $stocks[] = new Stock((array) $row);
                else:
                    $stocks[] = (array) $row;
                endif;
            endforeach;
            return $stocks;
        else:
            return FALSE;
        endif;
    }

}
