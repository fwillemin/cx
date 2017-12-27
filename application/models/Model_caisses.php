<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_caisses extends CI_Model {

    protected $table = 'caisse';

    /**
     * Ajout d'un objet de la classe Caisse à la BDD
     * @param Caisse $caisse Objet de la classe Caisse
     */
    public function ajouter(Caisse $caisse) {
        $this->db
                ->set('caissePdvId', $caisse->getCaissePdvId())
                ->set('caisseDate', $caisse->getCaisseDate())
                ->set('caisseMontant', $caisse->getCaisseMontant())
                ->set('caisseType', $caisse->getCaisseType())
                ->set('caisseDetail', $caisse->getCaisseDetail())
                ->insert($this->table);
        $caisse->setCaisseId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Caisse
     * @param Caisse $caisse Objet de la classe Caisse
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Caisse $caisse) {
        $this->db
                ->set('caissePdvId', $caisse->getCaissePdvId())
                ->set('caisseDate', $caisse->getCaisseDate())
                ->set('caisseMontant', $caisse->getCaisseMontant())
                ->set('caisseType', $caisse->getCaisseType())
                ->set('caisseDetail', $caisse->getCaisseDetail())
                ->where('caisseId', $caisse->getCaisseId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe caisse
     *
     * @param Caisse Objet de la classe Caisse
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Caisse $caisse) {
        $this->db->where('caisseId', $caisse->getCaisseId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function liste($where = array(), $tri = 'c.caisseDate ASC', $retour = 'object') {
        $query = $this->db->select('c.*')
                ->from($this->table . ' c')
                ->where('c.caissePdvId', $this->session->userdata('loggedPdvId'))
                ->where($where)
                ->order_by($tri)
                ->get();

        if ($query->num_rows() > 0):
            foreach ($query->result() AS $row):
                if ($retour == 'object'):
                    $caisses[] = new Caisse((array) $row);
                else:
                    $caisses[] = (array) $row;
                endif;
            endforeach;
            return $caisses;
        else:
            return FALSE;
        endif;
    }

    public function getCaisseById($caisseId, $type = 'object') {
        $query = $this->db->select('c.*')
                ->from($this->table . ' c')
                ->where('c.caissePdvId', $this->session->userdata('loggedPdvId'))
                ->where('c.caisseId', intval($caisseId))
                ->get();

        if ($query->num_rows() > 0):
            if ($type == 'object'):
                $caisse = new Caisse((array) $query->row());
            else:
                $caisse = (array) $query->row();
            endif;
            return $caisse;
        else:
            return FALSE;
        endif;
    }

    /* Retourne le dernier contrôle de fond de caisse précédent le début de la recherche */

    public function dernierControle($start, $type = 'object') {
        $query = $this->db->select('c.*')
                ->from($this->table . ' c')
                ->where(array('c.caissePdvId' => $this->session->userdata('loggedPdvId'), 'caisseDate <=' => $start, 'caisseType' => 2))
                ->limit(1, 0)
                ->get();
        if ($query->num_rows() > 0):
            if ($type == 'object'):
                $caisse = new Caisse((array) $query->row());
            else:
                $caisse = (array) $query->row();
            endif;
            return $caisse;
        else:
            return FALSE;
        endif;
    }

}
