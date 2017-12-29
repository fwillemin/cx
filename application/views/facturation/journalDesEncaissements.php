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
            <h1>Journal des encaissements</h1>
            <span style="font-size:13px; font-weight: bold;">
                Période du <?= date('d/m/Y', $debut) . ' au ' . date('d/m/Y', $fin); ?>
            </span>
        </td>
    </tr>
</table>
<br>
<br>
<br>
<table border="1" style="font-size:10px;">
    <tr style="background-color: lightgray;">
        <td style="width: 80px;">Date</td>
        <td style="width: 80px;">Fact</td>
        <td style="width: 220px;">Client</td>
        <td style="width: 80px; text-align: right;">Total</td>
        <td style="width: 100px;">Type</td>
    </tr>
    <?php
    $totalCB = 0;
    $totalCHE = 0;
    $totalESP = 0;
    $totalVIR = 0;
    $totalTRA = 0;
    if (!empty($reglements)):
        ?>
        <tr>
            <td colspan="5" style="background-color: lightgoldenrodyellow; font-weight: bold;">Réglements</td>               
        </tr>
        <?php
        foreach ($reglements as $r):
            switch ($r->getReglementModeId()):
                case 1 :
                    $totalCB += $r->getReglementTotal();
                    break;
                case 2 :
                    $totalCHE += $r->getReglementTotal();
                    break;
                case 3 :
                    $totalESP += $r->getReglementTotal();
                    break;
                case 4 :
                    $totalTRA += $r->getReglementTotal();
                    break;
                case 5 :
                    $totalVIR += $r->getReglementTotal();
                    break;
            endswitch;
            ?>
            <tr>
                <td>
                    <?php echo date('d/m/Y', $r->getReglementDate()); ?>
                </td>
                <td>
                    <?php echo $r->getReglementFactureId(); ?>
                </td>
                <td>
                    
                        <?php
                        if ($r->getReglementClient()->getClientType() == 1):
                            echo $r->getReglementClient()->getClientNom();
                        else:
                            echo $r->getReglementClient()->getClientRaisonSociale();
                        endif;
                        ?>
                   
                </td>
                <td style="text-align: right;">
                    <?php echo number_format($r->getReglementTotal(), 2, ',', ' ') . '€'; ?>
                </td>
                <td>
                    <?php echo $r->getReglementMode()->getModeReglementNom(); ?>
                </td>
            </tr>
            <?php
        endforeach;
    endif;

    if (!empty($acomptes)):
        ?>
        <tr>
            <td colspan="5" style="background-color: lightgoldenrodyellow; font-weight: bold;">Acomptes</td>               
        </tr>
        <?php
        foreach ($acomptes as $a):
            switch ($a->getAcompteModeReglementId()):
                case 1 :
                    $totalCB += $a->getAcompteTotal();
                    break;
                case 2 :
                    $totalCHE += $a->getAcompteTotal();
                    break;
                case 3 :
                    $totalESP += $a->getAcompteTotal();
                    break;
                case 4 :
                    $totalTRA += $a->getAcompteTotal();
                    break;
                case 5 :
                    $totalVIR += $a->getAcompteTotal();
                    break;
            endswitch;
            ?>
            <tr>
                <td>
                    <?php echo date('d/m/Y', $a->getAcompteDate()); ?>
                </td>
                <?php if ($a->getAcompteFactureId() > 0): ?>
                    <td>
                        <?php echo $a->getAcompteFactureId(); ?>
                    </td>
                    <td>
                        Acompte BDC <?php echo $a->getAcompteBdcId(); ?>
                    </td>
                <?php else:
                    ?>
                    <td colspan="2" style="text-align: center;">
                        Acompte bon de commande <?php echo $a->getAcompteBdcId(); ?>
                    </td>
                <?php endif; ?>
                <td style="text-align: right;">
                    <?php echo number_format($a->getAcompteTotal(), 2, ',', ' ') . '€'; ?>
                </td>
                <td>
                    <?php echo $a->getAcompteModeReglement()->getModeReglementNom(); ?>
                </td>
            </tr>
            <?php
        endforeach;
    endif;
    ?>
    <tr>
        <td colspan="5">
            <h4>Résumé</h4>
        </td>
    </tr>
    <tr>
        <td colspan="3" rowspan="5" id="pieModesReglement"></td>
        <td>Espèces</td>
        <td style="text-align:right;"><?php echo number_format($totalESP, 2, ',', ' ') . '€'; ?></td>
    </tr>
    <tr>
        <td>Chèques</td>
        <td style="text-align:right;"><?php echo number_format($totalCHE, 2, ',', ' ') . '€'; ?></td>
    </tr>
    <tr>
        <td>CB</td>
        <td style="text-align:right;"><?php echo number_format($totalCB, 2, ',', ' ') . '€'; ?></td>
    </tr>
    <tr>
        <td>Virement</td>
        <td style="text-align:right;"><?php echo number_format($totalVIR, 2, ',', ' ') . '€'; ?></td>
    </tr>
    <tr>
        <td>Traite</td>
        <td style="text-align:right;"><?php echo number_format($totalTRA, 2, ',', ' ') . '€'; ?></td>
    </tr>

</table>