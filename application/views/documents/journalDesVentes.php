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
                <td><?= date('d/m/Y', $f->getFactureDate()); ?></td>
                <td><?= $f->getFactureId(); ?></td>
                <td>
                    <?php
                    if ($f->getFactureClient()->getClientType() == 1):
                        echo $f->getFactureClient()->getClientNom();
                    else:
                        echo $f->getFactureClient()->getClientRaisonSociale();
                    endif;
                    ?>
                </td>
                <td style="text-align: right;"><?= number_format($f->getFactureTotalHT(), 2, ',', ' '); ?></td>
                <td style="text-align: right;"><?= number_format($f->getFactureTotalTVA(), 2, ',', ' '); ?></td>
                <td style="text-align: right;"><?= number_format($f->getFactureTotalTTC(), 2, ',', ' '); ?></td>
            </tr>
            <?php
        endforeach;
    endif;
    if ($avoirs):
        echo '<tr><td colspan="6" style="font-weight: bold; background-color:gold;">Avoirs de la période</td></tr>';
        foreach ($avoirs as $a):
            $totalHTPeriode -= $a->getAvoirTotalHT();
            $totalTVAPeriode -= $a->getAvoirTotalTVA();
            $totalTTCPeriode -= $a->getAvoirTotalTTC();
            ?>
            <tr>
                <td><?= date('d/m/Y', $a->getAvoirDate()); ?></td>
                <td><?= $a->getAvoirId(); ?></td>
                <td>
                    <?php
                    if ($a->getAvoirClient()->getClientType() == 1):
                        echo $a->getAvoirClient()->getClientNom();
                    else:
                        echo $a->getAvoirClient()->getClientRaisonSociale();
                    endif;
                    ?>
                </td>
                <td style="text-align: right;"><?= '-' . number_format($a->getAvoirTotalHT(), 2, ',', ' '); ?></td>
                <td style="text-align: right;"><?= '-' . number_format($a->getAvoirTotalTVA(), 2, ',', ' '); ?></td>
                <td style="text-align: right;"><?= '-' . number_format($a->getAvoirTotalTTC(), 2, ',', ' '); ?></td>
            </tr>
        <?php endforeach; ?>

        <tr>
            <td colspan="3" style="text-align: right; font-weight: bold;">Totaux</td>
            <td style="text-align: right; font-weight: bold;"><?= number_format($totalHTPeriode, 2, ',', ' ') . '€'; ?></td>
            <td style="text-align: right; font-weight: bold;"><?= number_format($totalTVAPeriode, 2, ',', ' ') . '€'; ?></td>
            <td style="text-align: right; font-weight: bold;"><?= number_format($totalTTCPeriode, 2, ',', ' ') . '€'; ?></td>
        </tr>
    <?php endif;
    ?>

</table>