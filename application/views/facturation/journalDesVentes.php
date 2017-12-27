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
            <h1>Journal des ventes</h1>
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
    <tr style="background-color: lightgray;">
        <td style="width: 80px;">Date</td>
        <td style="width: 50px;">Fact</td>
        <td style="width: 220px;">Client</td>
        <td style="text-align: right; width: 70px;">Total HT</td>
        <td style="text-align: right; width: 70px;">TVA</td>
        <td style="text-align: right; width: 70px;">Total TTC</td>
    </tr>

    <?php
    if (!empty($factures)):
        $totalHTPeriode = 0;
        $totalTVAPeriode = 0;
        $totalTTCPeriode = 0;
        foreach ($factures as $f):
            $totalHTPeriode += $f->getFactureTotalHT();
            $totalTVAPeriode += $f->getFactureTotalTVA();
            $totalTTCPeriode += $f->getFactureTotalTTC();
            ?>
            <tr>
                <td><?php echo date('d/m/Y', $f->getFactureDate()); ?></td>
                <td><?php echo $f->getFactureId(); ?></td>
                <td>
                    <?php
                    if ($f->getFactureClient()->getClientType() == 1):
                        echo $f->getFactureClient()->getClientNom();
                    else:
                        echo $f->getFactureClient()->getClientRaisonSociale();
                    endif;
                    ?>
                </td>
                <td style="text-align: right;"><?php echo number_format($f->getFactureTotalHT(), 2, ',', ' '); ?></td>
                <td style="text-align: right;"><?php echo number_format($f->getFactureTotalTVA(), 2, ',', ' '); ?></td>
                <td style="text-align: right;"><?php echo number_format($f->getFactureTotalTTC(), 2, ',', ' '); ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="3" style="text-align: right; font-weight: bold;">Totaux</td>
            <td style="text-align: right; font-weight: bold;"><?php echo number_format($totalHTPeriode, 2, ',', ' ') . '€'; ?></td>
            <td style="text-align: right; font-weight: bold;"><?php echo number_format($totalTVAPeriode, 2, ',', ' ') . '€'; ?></td>
            <td style="text-align: right; font-weight: bold;"><?php echo number_format($totalTTCPeriode, 2, ',', ' ') . '€'; ?></td>
        </tr>
    <?php endif;
    ?>

</table>