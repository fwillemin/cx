<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cxwork {

    function array_msort($array, $cols) {
        $colarr = array();
        foreach ($cols as $col => $order) {
            $colarr[$col] = array();
            foreach ($array as $k => $row) {
                $colarr[$col]['_' . $k] = strtolower($row[$col]);
            }
        }
        $eval = 'array_multisort(';
        foreach ($cols as $col => $order) {
            $eval .= '$colarr[\'' . $col . '\'],' . $order . ',';
        }
        $eval = substr($eval, 0, -1) . ');';
        eval($eval);
        $ret = array();
        foreach ($colarr as $col => $arr) {
            foreach ($arr as $k => $v) {
                $k = substr($k, 1);
                if (!isset($ret[$k]))
                    $ret[$k] = $array[$k];
                $ret[$k][$col] = $array[$k][$col];
            }
        }
        return $ret;
    }

    function mktimeFromInputDate($input = null) {
        date_default_timezone_set('Europe/Paris');
        if ($input == '' || !$input || $input == 0): return 0;
        else:
            $temp = explode('-', $input);
            return mktime(0, 0, 0, $temp[1], $temp[2], $temp[0]);
        endif;
    }

    public function affUnite($unite) {
        switch ($unite) {
            case 1:
                return 'pièce';
                break;
            case 2:
                return 'm²';
                break;
            case 3:
                return 'ml';
                break;
        }
    }

    public function affVenteType($type) {
        switch ($type) {
            case 1:
                return 'devis';
                break;
            case 2:
                return 'bon de commande';
                break;
            case 3:
                return 'bon de livraison';
                break;
            case 4:
                return 'facture';
                break;
        }
    }

    public function affCommandeEtat($etat) {
        switch ($etat) {
            default:
                return 'En attente de confimation';
                break;
            case 0:
                return 'En cours';
                break;
            case 2:
                return 'Livraison partielle';
                break;
            case 3:
                return 'Livré';
                break;
        }
    }

    public function affModeReglement($mode) {
        switch ($mode):
            case 0: return '';
                break;
            case 1: return 'Carte bancaire';
                break;
            case 2: return 'Chèque';
                break;
            case 3: return 'Espèces';
                break;
            case 4: return 'Traite';
                break;
            case 5: return 'Virement';
                break;
            case 6: return 'Acompte';
                break;
        endswitch;
    }

    public function calculEcheance($emission, $condition) {

        /* calcul de l'échéance de paiement */
        $echJour = date('d', $emission);
        $echMois = date('m', $emission);
        $echAnnee = date('Y', $emission);
        $delai = substr($condition, 0, 2);
        $cond = substr($condition, 2, 3);
        $nbJourMoisFacturation = date('t', $emission) - $echJour;
        /* somme des jours du mois de facturation et du mois suivant */
        if ($echMois == 12)
            $nbJourMoisEtSuivant = $nbJourMoisFacturation + date('t', mktime(1, 0, 0, 1, 1, $echAnnee + 1));
        else
            $nbJourMoisEtSuivant = $nbJourMoisFacturation + date('t', mktime(1, 0, 0, $echMois + 1, 1, $echAnnee));
        switch ($cond):
            case 'JFM':
                if ($delai + $echJour > $nbJourMoisEtSuivant)
                    $echMois += 2;
                else
                    $echMois++;
                if ($echMois > 12):
                    $echMois = $echMois - 12;
                    $echAnnee++;
                endif;
                $echJour = date('t', mktime(1, 0, 0, $echMois, 1, $echAnnee));
                $echeance = mktime(0,0,0, $echMois, $echJour, $echAnnee);
                break;
            case 'JDF':
                $echeance = $emission + $delai * 86400;
                break;
            case 'J15':
                $echMois = date('m', $emission + 86400 * $delai);
                $echAnnee = date('Y', $emission + 86400 * $delai);
                if (date('d', $emission + 86400 * $delai) > 15)
                    $echMois ++;
                if ($echMois > 12):
                    $echMois = 1;
                    $echAnnee++;
                endif;
                $echeance = mktime(0,0,0, $echMois, '15', $echAnnee);
                break;
            default:
                $echeance = $emission;
                break;
        endswitch;
        
        return $echeance;
    }

}

?>
