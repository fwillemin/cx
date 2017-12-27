<table>
    <tr style="font-size:10px;">
        <td>
            <img src="<?= base_url('assets/logos/' . $pdv->getPdvId() . '.png'); ?>" style="height:40px;">
            <br><?php
            echo $pdv->getPdvAdresse1();
            if ($pdv->getPdvAdresse2()):
                echo '<br>' . $pdv->getPdvAdresse2();
            endif;
            echo '<br>' . $pdv->getPdvCp() . ' ' . $pdv->getPdvVille() . '<br>Téléphone : ' . $pdv->getPdvTelephone() . '<br>Email : ' . $pdv->getPdvEmail();
            echo '<br><span style="font-weight: bold; color: darkblue;">' . $pdv->getPdvWww() . '</span>';
            if ($pdv->getPdvTelephoneCommercial() || $pdv->getPdvEmailCommercial()):
                echo '<br><span style="text-decoration: underline; font-weight: bold;">Service commercial :</span>';
                if ($pdv->getPdvTelephoneCommercial()):
                    echo '<br>Téléphone : ' . $pdv->getPdvTelephoneCommercial();
                endif;
                if ($pdv->getPdvEmailCommercial()):
                    echo '<br>Email : ' . $pdv->getPdvEmailCommercial();
                endif;
            endif;
            if ($pdv->getPdvTelephoneTechnique() || $pdv->getPdvEmailTechnique()):
                echo '<br><span style="text-decoration: underline; font-weight: bold;">Service technique :</span>';
                if ($pdv->getPdvTelephoneTechnique()):
                    echo '<br>Téléphone : ' . $pdv->getPdvTelephoneTechnique();
                endif;
                if ($pdv->getPdvEmailTechnique()):
                    echo '<br>Email : ' . $pdv->getPdvEmailTechnique();
                endif;
            endif;
            ?>
        </td>
        <td style="text-align: right;">
            Le <?= date('d/m/Y'); ?>
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
        <td style="width: 65px;">Date</td>
        <td style="width: 60px;">Type</td>
        <td style="width: 60px;">Fact</td>
        <td style="width: 220px;">Client</td>
        <td style="width: 70px; text-align: right;">Total</td>
        <td style="width: 85px;">Mode</td>
    </tr>
    <?php
    $totalCB = 0;
    $totalCHE = 0;
    $totalESP = 0;
    $totalVIR = 0;
    $totalTRA = 0;
    if ($reglements):
        foreach ($reglements as $r):
            switch ($r->getReglementModeId()):
                case 1 :
                    $totalCB += $r->getReglementMontant();
                    break;
                case 2 :
                    $totalCHE += $r->getReglementMontant();
                    break;
                case 3 :
                    $totalESP += $r->getReglementMontant();
                    break;
                case 4 :
                    $totalTRA += $r->getReglementMontant();
                    break;
                case 5 :
                    $totalVIR += $r->getReglementMontant();
                    break;
            endswitch;
            ?>
            <tr>
                <td>
                    <?= date('d/m/Y', $r->getReglementDate()); ?>
                </td>
                <td>
                    <?= $r->getReglementType() == 1 ? 'Acompte' : 'Solde'; ?>
                </td>
                <td>
                    <?= $r->getReglementFactureId(); ?>
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
                    <?= number_format($r->getReglementMontant(), 2, ',', ' ') . '€'; ?>
                </td>
                <td>
                    <?= $r->getReglementMode()->getModeReglementNom(); ?>
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
        <td colspan="4" rowspan="5" id="pieModesReglement"></td>
        <td>Espèces</td>
        <td style="text-align:right;"><?= number_format($totalESP, 2, ',', ' ') . '€'; ?></td>
    </tr>
    <tr>
        <td>Chèques</td>
        <td style="text-align:right;"><?= number_format($totalCHE, 2, ',', ' ') . '€'; ?></td>
    </tr>
    <tr>
        <td>CB</td>
        <td style="text-align:right;"><?= number_format($totalCB, 2, ',', ' ') . '€'; ?></td>
    </tr>
    <tr>
        <td>Virement</td>
        <td style="text-align:right;"><?= number_format($totalVIR, 2, ',', ' ') . '€'; ?></td>
    </tr>
    <tr>
        <td>Traite</td>
        <td style="text-align:right;"><?= number_format($totalTRA, 2, ',', ' ') . '€'; ?></td>
    </tr>

</table>