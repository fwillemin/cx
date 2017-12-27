<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_pdv extends MY_model {

    protected $table = 'pdv';

    const classe = 'Pdv';

    /**
     * Ajout d'un objet de la classe Pdv à la BDD
     * @param Pdv $pdv Objet de la classe Pdv
     */
    public function ajouter(Pdv $pdv) {
        $this->db
                ->set('pdvRaisonSociale', $pdv->getPdvRaisonSociale())
                ->set('pdvNomCommercial', $pdv->getPdvNomCommercial())
                ->set('pdvAdresse1', $pdv->getPdvAdresse1())
                ->set('pdvAdresse2', $pdv->getPdvAdresse2())
                ->set('pdvCp', $pdv->getPdvCp())
                ->set('pdvVille', $pdv->getPdvVille())
                ->set('pdvTelephone', $pdv->getPdvTelephone())
                ->set('pdvEmail', $pdv->getPdvEmail())
                ->set('pdvFax', $pdv->getPdvFax())
                ->set('pdvWww', $pdv->getPdvWww())
                ->set('pdvTelephoneCommercial', $pdv->getPdvTelephoneCommercial())
                ->set('pdvEmailCommercial', $pdv->getPdvEmailCommercial())
                ->set('pdvTelephoneTechnique', $pdv->getPdvTelephoneTechnique())
                ->set('pdvEmailTechnique', $pdv->getPdvEmailTechnique())
                ->set('pdvSiren', $pdv->getPdvSiren())
                ->set('pdvTvaIntracom', $pdv->getPdvTvaIntracom())
                ->set('pdvApe', $pdv->getPdvApe())
                ->insert($this->table);
        $pdv->setPdvId($this->db->insert_id());
    }

    /**
     * Mise à jour de la BDD pour un objet de la classe Pdv
     * @param Pdv $pdv Objet de la classe Pdv
     * @return integer Renvoi le nombre d'enregistrements modifiés
     */
    public function editer(Pdv $pdv) {
        $this->db
                ->set('pdvRaisonSociale', $pdv->getPdvRaisonSociale())
                ->set('pdvNomCommercial', $pdv->getPdvNomCommercial())
                ->set('pdvAdresse1', $pdv->getPdvAdresse1())
                ->set('pdvAdresse2', $pdv->getPdvAdresse2())
                ->set('pdvCp', $pdv->getPdvCp())
                ->set('pdvVille', $pdv->getPdvVille())
                ->set('pdvTelephone', $pdv->getPdvTelephone())
                ->set('pdvEmail', $pdv->getPdvEmail())
                ->set('pdvFax', $pdv->getPdvFax())
                ->set('pdvWww', $pdv->getPdvWww())
                ->set('pdvTelephoneCommercial', $pdv->getPdvTelephoneCommercial())
                ->set('pdvEmailCommercial', $pdv->getPdvEmailCommercial())
                ->set('pdvTelephoneTechnique', $pdv->getPdvTelephoneTechnique())
                ->set('pdvEmailTechnique', $pdv->getPdvEmailTechnique())
                ->set('pdvSiren', $pdv->getPdvSiren())
                ->set('pdvTvaIntracom', $pdv->getPdvTvaIntracom())
                ->set('pdvApe', $pdv->getPdvApe())
                ->where('pdvId', $pdv->getPdvId())
                ->update($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Suppression d'un objet de la classe pdv
     *
     * @param Pdv Objet de la classe Pdv
     * @return integer Retourne le nombre d'enregistrements supprimés
     */
    public function delete(Pdv $pdv) {
        $this->db->where('pdvId', $pdv->getPdvPdvId())
                ->delete($this->table);
        return $this->db->affected_rows();
    }

    public function getPdvById($pdvId, $type = 'object') {
        $query = $this->db->select('*')
                ->from($this->table)
                ->where(array('pdvId' => $pdvId))
                ->get();
        return $this->retourne($query, $type, self::classe, true);
    }

}
