<?php

/**
 * Classe de création de Token sécurisés
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Token {

    protected $tokenKey;

    public function __construct(array $valeurs = []) {
        /* Si on passe des valeurs, on hydrate l'objet */
        if (!empty($valeurs))
            $this->hydrate($valeurs);
    }

    public function getToken($chaine) {
        
        $options = [
            'cost' => 10,
        ];
        $token =  password_hash( $chaine, PASSWORD_BCRYPT, $options);
        
        if( password_verify($chaine, $token) ):        
            return $token;
        else:
            return false;
        endif;
        
    }
    
    public function verifyToken( $chaine, $token ) {
                
        if( password_verify($chaine, $token) ):
            return true;
        else:
            return false;
        endif;
        
    }

}
