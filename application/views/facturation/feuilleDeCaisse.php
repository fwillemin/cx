<table>
    <tr style="font-size:10px;">
        <td>
            <img src="<?php echo base_url('assets/img/logoSmall.png'); ?>" style="height:40px;"><br>
            SARL AUX CARREAUX DE MAX<br>
            21, Av Maréchal Leclerc de Hautecloque<br>
            59360 LE CATEAU CAMBRESIS<br>
            Téléphone : 03.27.77.42.46<br>
            <span style="font-weight:bold; color:#2e6da4;">www.carreauximportnegoce.fr</span><br>
            Commercial : carreaux-import-negoce@orange.fr<br>
            Direction : ledieumaxime59@orange.fr
        </td>
        <td style="text-align: right;">
            Le <?php echo date('d/m/Y'); ?>
            <h1>Feuille de caisse</h1>
            <span style="font-size:13px; font-weight: bold;">
                Période du <?= date('d/m/Y', $debut) . ' au ' . date('d/m/Y', $fin); ?> 
            </span>          
        </td>
    </tr>        
</table>
<br>
<br>
<br>
<table border="1" style="font-size:11px;">
    <thead>
        <tr style="background-color: lightgrey;">
            <th style="width: 80px;">Date</th>
            <th style="width: 200px;">Objet</th>
            <th style="text-align: right;">Montant</th>
            <th style="text-align: right;">Solde</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $solde = 0;
        foreach ($caisse as $c):
            switch ($c['origine']):
                case 'caisse':
                    if ($c['type'] == 1): /* sortie de caisse */
                        $solde -= $c['montant'];
                    else:
                        if ($c['montant'] != $solde && $c['date'] >= $debut)
                            echo '<tr style="color:#a94442; background-color:#f2dede;"><td colspan="4" style="text-align:center;">Ecart constaté : ' . number_format(round($solde - $c['montant'], 2), 2, ',', ' ') . '€</td></tr>'; /* génération d'un écart */
                        $solde = $c['montant'];
                    endif;
                    break;
                case 'reglement':
                    $solde += $c['montant'];
                    break;
                case 'acompte':
                    $solde += $c['montant'];
                    break;
            endswitch;
            if ($c['date'] >= $debut):
                ?>
                <tr>
                    <td style="width:80px;"><?php echo date('d/m/Y', $c['date']); ?></td>
                    <td style="width:200px;">
                        <?php
                        if ($c['origine'] == 'caisse'):
                            if ($c['type'] == 1):
                                $objet = '<strong>Sortie de caisse</strong><br>' . $c['objet'];
                            else:
                                $objet = 'Fond de caisse';
                            endif;
                        elseif ($c['origine'] == 'acompte'):
                            $objet = 'Acompte Bon de commande ' . $c['objet'];
                        else:
                            $objet = $c['type'] . ' ' . $c['objet'];
                        endif;
                        echo $objet;
                        ?>
                    </td>
                    <td style="text-align: right;"><?php echo number_format($c['montant'], 2, ',', ' ') . '€'; ?></td>
                    <td style="text-align: right;"><?php echo number_format($solde, 2, ',', ' ') . '€'; ?></td>
                </tr>
                <?php
            endif;
        endforeach;
        ?>
        <tr><td colspan="4"></td></tr>
        <tr style="font-size: 15px; font-weight: bold;">
            <td colspan="3" style="text-align: right;">Solde de la caisse au <?php echo date('d/m/Y', $fin); ?></td>
            <td style="text-align: right;"><?php echo number_format($solde, 2, ',', ' ') . '€'; ?></td>
        </tr>
    </tbody>
</table>

