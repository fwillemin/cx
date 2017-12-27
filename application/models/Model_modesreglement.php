<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_modesreglement extends CI_Model {

    protected $table = 'modesreglement';

    /**
     * Ajout d'un objet de la classe Modereglement à la BDD
     * @param Modereglement $modereglement Objet de la classe Modereglement
     */
    public function ajouter(Modereglement $modereglement) {
        $this->db
                ->set('modeReglementNom', $modereglement->getModereglementNom())
                ->insert($this->table);
        $modereglement->setModereglementId($this->db->insert_id());
    }

    public function liste($where = array(), $retour = 'object') {
        $query = $this->db->select('m.*')
                ->from($this->table . ' m')
                ->where($where)
                ->order_by('m.modeReglementNom ASC')
                ->get();

        if ($query->num_rows() > 0):
            foreach ($query->result() AS $row):
                if ($retour == 'object'):
                    $modes[] = new Modereglement((array) $row);
                else:
                    $modes[] = (array) $row;
                endif;
            endforeach;
            return $modes;
        else:
            return FALSE;
        endif;
    }

    public function getModeReglementById($modereglementId) {
        $query = $this->db->select('m.*')
                ->from('modesreglement m')
                ->where(array('m.modereglementId' => intval($modereglementId)))
                ->get();
        if ($query->num_rows() > 0):
            $modereglement = new Modereglement((array) $query->row());
            return $modereglement;
        else:
            return FALSE;
        endif;
    }

}
