<?php

/**
 * Classe de gestion des Acomptes.
 * Manager : Model_acomptes
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Acompte {

    protected $acompteId;
    protected $acompteFactureId;
    protected $acompteBdcId;
    protected $acompteBdc;
    protected $acompteTotal;
    protected $acompteModeReglementId;
    protected $acompteModeReglement;
    protected $acompteDate;

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
        $this->setAcompteModeReglement( $CI->managerModesreglement->getModeReglementById( $this->getAcompteModeReglementId() ) );
    }
    
    function hydrateBdc() {
        $CI =& get_instance();
        $this->acompteBdc = $CI->managerBdc->getBdcById( $this->acompteBdcId );
    }

    function getAcompteId() {
        return $this->acompteId;
    }

    function getAcompteFactureId() {
        return $this->acompteFactureId;
    }

    function getAcompteBdcId() {
        return $this->acompteBdcId;
    }

    function getAcompteTotal() {
        return $this->acompteTotal;
    }

    function getAcompteModeReglementId() {
        return $this->acompteModeReglementId;
    }

    function getAcompteModeReglement() {
        return $this->acompteModeReglement;
    }

    function getAcompteDate() {
        return $this->acompteDate;
    }

    function setAcompteId($acompteId) {
        $this->acompteId = $acompteId;
    }

    function setAcompteFactureId($acompteFactureId) {
        $this->acompteFactureId = $acompteFactureId;
    }

    function setAcompteBdcId($acompteBdcId) {
        $this->acompteBdcId = $acompteBdcId;
    }

    function setAcompteTotal($acompteTotal) {
        $this->acompteTotal = $acompteTotal;
    }

    function setAcompteModeReglementId($acompteModeReglementId) {
        $this->acompteModeReglementId = $acompteModeReglementId;
    }

    function setAcompteModeReglement($acompteModeReglement) {
        $this->acompteModeReglement = $acompteModeReglement;
    }

    function setAcompteDate($acompteDate) {
        $this->acompteDate = $acompteDate;
    }
    
    function getAcompteBdc() {
        return $this->acompteBdc;
    }

    function setAcompteBdc($acompteBdc) {
        $this->acompteBdc = $acompteBdc;
    }
}
