<?php

/**
 * Classe de généraliste du site
 *
 * @author Xanthellis - WILLEMIN François - http://www.xanthellis.com
 */
class Own {

    static $forbidden_caract = array(
        ' ' => '-',
        '!' => '-',
        '?' => '-',
        '(' => '-',
        ')' => '-',
        '[' => '-',
        ']' => '-',
        '+' => '',
        '*' => 'x',
        ',' => '-',
        '&' => '-',
        'é' => 'e',
        'è' => 'e',
        'ê' => 'e',
        'à' => 'a',
        'â' => 'a',
        'ä' => 'a',
        'À' => 'A',
        'ù' => 'u',
        '/' => '-',
        'ô' => 'o',
        '’' => '-',
        '\'' => '-',
        '"' => '',
        ';' => '',
        'ö' => 'o',
        'É' => 'E',
        'ü' => 'u',
        '€' => 'E',
        '°' => '',
        'î' => 'i'
    );

    function mktimeFromInputDate($input = null) {
        date_default_timezone_set('Europe/Paris');
        if ($input == '' || !$input || $input == 0): return 0;
        else:
            $temp = explode('-', $input);
            return mktime(0, 0, 0, $temp[1], $temp[2], $temp[0]);
        endif;
    }

    public function cleanCharactersUrl($string) {
        $cleanString = strtr(preg_replace('#[^[:alnum:]\ ]#u', '', $string), self::$forbidden_caract);
        return $cleanString;
    }

    private function _enteteEmail(Pdv $pdv) {

        $code = '<!DOCTYPE HTML>'
                . '<html xmlns="http://www.w3.org/1999/xhtml">'
                . '<head>'
                . '<title>Document envoyé par ' . $pdv->getPdvNomCommercial() . '</title>'
                . '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">'
                . '<meta name="viewport" content="width=device-width, initial-scale=1.0">'
                . '</head>'
                . '<body style="margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px; background-color: #FFF;">'
                . '<table width="100%" height="100%" cellpadding="0" style="padding: 20px 0px 20px 0px">'
                . '<tr><td style="width:15px;"></td><td>';
        return $code;
    }

    private function _footerEmail() {

        $code = '</td><td style="width:15px;"></td></tr>'
                . '</table>'
                . '</body>'
                . '</html>';

        return $code;
    }

    public function emailDevis(Devis $devis, Pdv $pdv) {

        $CI = &get_instance();
        $CI->email->from($pdv->getPdvEmail(), $pdv->getPdvNomCommercial());
        $CI->email->to($devis->getDevisClient()->getClientEmail());
        $CI->email->subject('Votre devis ' . $pdv->getPdvNomCommercial());

        /* Création du message */
        $message = $this->_enteteEmail($pdv);

        $message .= 'Madame, Monsieur,'
                . '<p>Veuillez trouver ci-joint votre devis.'
                . '<br>Nous sommes à votre disposition pour de plus amples informations</p>'
                . 'Merci et à bientôt'
                . '<br><br>L\'équipe ' . $pdv->getPdvNomCommercial();
        ;

        $message .= $this->_footerEmail();

        $CI->email->message($message);
        $CI->email->attach('assets/Devis ' . $devis->getDevisId() . '.pdf');
        return $CI->email->send();
    }

    public function emailBdc(Bdc $bdc, Pdv $pdv) {

        $CI = &get_instance();
        $CI->email->from($pdv->getPdvEmail(), $pdv->getPdvNomCommercial());
        $CI->email->to($bdc->getBdcClient()->getClientEmail());
        $CI->email->subject('Votre bon de commande ' . $pdv->getPdvNomCommercial());

        /* Création du message */
        $message = $this->_enteteEmail($pdv);

        $message .= 'Madame, Monsieur,'
                . '<p>Veuillez trouver ci-joint votre bon de commande.'
                . '<br>Nous sommes à votre disposition pour de plus amples informations</p>'
                . 'Merci et à bientôt'
                . '<br><br>L\'équipe ' . $pdv->getPdvNomCommercial();
        ;

        $message .= $this->_footerEmail();

        $CI->email->message($message);
        $CI->email->attach('assets/Commande ' . $bdc->getBdcId() . '.pdf');
        return $CI->email->send();
    }

    public function emailBl(Bl $bl, Pdv $pdv) {

        $CI = &get_instance();
        $CI->email->from($pdv->getPdvEmail(), $pdv->getPdvNomCommercial());
        $CI->email->to($bl->getBlClient()->getClientEmail());
        $CI->email->subject('Votre bon de livraison ' . $pdv->getPdvNomCommercial());

        /* Création du message */
        $message = $this->_enteteEmail($pdv);

        $message .= 'Madame, Monsieur,'
                . '<p>Veuillez trouver ci-joint votre bon de livraison pour la commande ' . $bl->getBlBdcId()
                . '<br>Nous sommes à votre disposition pour de plus amples informations</p>'
                . 'Merci et à bientôt'
                . '<br><br>L\'équipe ' . $pdv->getPdvNomCommercial();
        ;

        $message .= $this->_footerEmail();

        $CI->email->message($message);
        $CI->email->attach('assets/Bl ' . $bl->getBlId() . '.pdf');
        return $CI->email->send();
    }

    public function emailFacture(Facture $facture, Pdv $pdv) {

        $CI = &get_instance();
        $CI->email->from($pdv->getPdvEmail(), $pdv->getPdvNomCommercial());
        $CI->email->to($facture->getFactureClient()->getClientEmail());
        $CI->email->subject('Votre Facture ' . $pdv->getPdvNomCommercial());

        /* Création du message */
        $message = $this->_enteteEmail($pdv);

        $message .= 'Madame, Monsieur,'
                . '<p>Veuillez trouver ci-joint votre facture N°' . $facture->getFactureId()
                . '<br>Nous sommes à votre disposition pour de plus amples informations</p>'
                . 'Merci et à bientôt'
                . '<br><br>L\'équipe ' . $pdv->getPdvNomCommercial();
        ;

        $message .= $this->_footerEmail();

        $CI->email->message($message);
        $CI->email->attach('assets/Facture ' . $facture->getFactureId() . '.pdf');
        return $CI->email->send();
    }

    public function emailAvoir(Avoir $avoir, Pdv $pdv) {

        $CI = &get_instance();
        $CI->email->from($pdv->getPdvEmail(), $pdv->getPdvNomCommercial());
        $CI->email->to($avoir->getAvoirClient()->getClientEmail());
        $CI->email->subject('Votre avoir ' . $pdv->getPdvNomCommercial());

        /* Création du message */
        $message = $this->_enteteEmail($pdv);

        $message .= 'Madame, Monsieur,'
                . '<p>Veuillez trouver ci-joint votre avoir N°' . $avoir->getAvoirId()
                . '<br>Nous sommes à votre disposition pour de plus amples informations</p>'
                . 'Merci et à bientôt'
                . '<br><br>L\'équipe ' . $pdv->getPdvNomCommercial();
        ;

        $message .= $this->_footerEmail();

        $CI->email->message($message);
        $CI->email->attach('assets/Avoir ' . $avoir->getAvoirId() . '.pdf');
        return $CI->email->send();
    }

}
