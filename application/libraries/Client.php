<?php

/**
 * Classe de gestion des Clients.
 * Manager : Model_clients
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Client {

    protected $clientId;
    protected $clientPdvId;
    protected $clientType;
    protected $clientCodeComptable;
    protected $clientRaisonSociale;
    protected $clientNom;
    protected $clientPrenom;
    protected $clientAdresse1;
    protected $clientAdresse2;
    protected $clientCp;
    protected $clientVille;
    protected $clientPays;    
    protected $clientTel;
    protected $clientPortable;
    protected $clientFax;
    protected $clientEmail;
    protected $clientExonerationTVA;
    protected $clientIntracom;
    protected $clientModeReglementId;
    protected $clientModeReglement;
    protected $clientConditionReglementId;
    protected $clientConditionReglement;

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
        $this->setClientConditionReglement( $CI->managerConditionsreglement->getConditionReglementById( $this->getClientConditionReglementId() ) );
        $this->setClientModeReglement( $CI->managerModesreglement->getModeReglementById( $this->getClientModeReglementId() ) );
    }
    
    function getClientId() {
        return $this->clientId;
    }

    function getClientPdvId() {
        return $this->clientPdvId;
    }

    function getClientType() {
        return $this->clientType;
    }

    function getClientCodeComptable() {
        return $this->clientCodeComptable;
    }

    function getClientNom() {
        return $this->clientNom;
    }

    function getClientPrenom() {
        return $this->clientPrenom;
    }

    function getClientAdresse1() {
        return $this->clientAdresse1;
    }

    function getClientAdresse2() {
        return $this->clientAdresse2;
    }

    function getClientCp() {
        return $this->clientCp;
    }

    function getClientVille() {
        return $this->clientVille;
    }

    function getClientPays() {
        return $this->clientPays;
    }

    function getClientTel() {
        return $this->clientTel;
    }

    function getClientPortable() {
        return $this->clientPortable;
    }

    function getClientFax() {
        return $this->clientFax;
    }

    function getClientEmail() {
        return $this->clientEmail;
    }

    function getClientIntracom() {
        return $this->clientIntracom;
    }

    function getClientModeReglement() {
        return $this->clientModeReglement;
    }

    function getClientConditionReglement() {
        return $this->clientConditionReglement;
    }

    function setClientId($clientId) {
        $this->clientId = $clientId;
    }

    function setClientPdvId($clientPdvId) {
        $this->clientPdvId = $clientPdvId;
    }

    function setClientType($clientType) {
        $this->clientType = $clientType;
    }

    function setClientCodeComptable($clientCodeComptable) {
        $this->clientCodeComptable = $clientCodeComptable;
    }

    function setClientNom($clientNom) {
        $this->clientNom = strtoupper($clientNom);
    }

    function setClientPrenom($clientPrenom) {
        $this->clientPrenom = $clientPrenom;
    }

    function setClientAdresse1($clientAdresse1) {
        $this->clientAdresse1 = strtoupper($clientAdresse1);
    }

    function setClientAdresse2($clientAdresse2) {
        $this->clientAdresse2 = strtoupper($clientAdresse2);
    }

    function setClientCp($clientCp) {
        $this->clientCp = $clientCp;
    }

    function setClientVille($clientVille) {
        $this->clientVille = strtoupper($clientVille);
    }

    function setClientPays($clientPays) {
        $this->clientPays = strtoupper($clientPays);
    }

    function setClientTel($clientTel) {
        $this->clientTel = $clientTel;
    }

    function setClientPortable($clientPortable) {
        $this->clientPortable = $clientPortable;
    }

    function setClientFax($clientFax) {
        $this->clientFax = $clientFax;
    }

    function setClientEmail($clientEmail) {
        $this->clientEmail = $clientEmail;
    }

    function setClientIntracom($clientIntracom) {
        $this->clientIntracom = $clientIntracom;
    }

    function setClientModeReglement($clientModeReglement) {
        $this->clientModeReglement = $clientModeReglement;
    }

    function setClientConditionReglement($clientConditionReglement) {
        $this->clientConditionReglement = $clientConditionReglement;
    }
    function getClientModeReglementId() {
        return $this->clientModeReglementId;
    }

    function getClientConditionReglementId() {
        return $this->clientConditionReglementId;
    }

    function setClientModeReglementId($clientModeReglementId) {
        $this->clientModeReglementId = intval($clientModeReglementId);
    }

    function setClientConditionReglementId($clientConditionReglementId) {
        $this->clientConditionReglementId = intval($clientConditionReglementId);
    }
    function getClientRaisonSociale() {
        return $this->clientRaisonSociale;
    }

    function setClientRaisonSociale($clientRaisonSociale) {
        $this->clientRaisonSociale = strtoupper($clientRaisonSociale);
    }
    function getClientExonerationTVA() {
        return $this->clientExonerationTVA;
    }

    function setClientExonerationTVA($clientExonerationTVA) {
        if( in_array( $clientExonerationTVA, array(0,1) ) ):
            $this->clientExonerationTVA = $clientExonerationTVA;
        else:
            $this->clientExonerationTVA = 0;
        endif;
    }
}
