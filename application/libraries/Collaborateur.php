<?php

/**
 * Classe de gestion des Collaborateurs.
 * Manager : Model_collaborateurs
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Collaborateur {

    protected $collaborateurId;
    protected $collaborateurPdvId;
    protected $collaborateurNom;
    protected $collaborateurActive;

    public function __construct(array $valeurs = []) {
        /* Si on passe des valeurs, on hydrate l'objet */
        if (!empty($valeurs))
            $this->hydrate($valeurs);
    }

    public function hydrate(array $donnees) {
        foreach ($donnees as $key => $value):
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method))
                $this->$method($value);
        endforeach;

        $CI = & get_instance();
    }

    function getCollaborateurId() {
        return $this->collaborateurId;
    }

    function getCollaborateurPdvId() {
        return $this->collaborateurPdvId;
    }

    function getCollaborateurNom() {
        return $this->collaborateurNom;
    }

    function getCollaborateurActive() {
        return $this->collaborateurActive;
    }

    function setCollaborateurId($collaborateurId) {
        $this->collaborateurId = $collaborateurId;
    }

    function setCollaborateurPdvId($collaborateurPdvId) {
        $this->collaborateurPdvId = $collaborateurPdvId;
    }

    function setCollaborateurNom($collaborateurNom) {
        $this->collaborateurNom = $collaborateurNom;
    }

    function setCollaborateurActive($collaborateurActive) {
        $this->collaborateurActive = $collaborateurActive;
    }

}
