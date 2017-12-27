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
        <td style = "text-align: right;">

            <table style="width:270px;" cellspacing="0" border="1" cellpadding="2">
                <tr>
                    <td colspan="3" style="text-align: center; font-weight: bold; height: 20px; font-size:15px;">
                        AVOIR
                    </td>
                </tr>
                <tr style="background-color: lightgrey; text-align: center; font-weight: bold;">
                    <td style="width: 90px;">N° avoir</td>
                    <td style="width: 90px;">Date</td>
                    <td style="width: 90px;">Facture liée</td>
                </tr>
                <tr style="text-align: center;">
                    <td style=" height: 20px;"><?= $avoir->getAvoirId(); ?></td>
                    <td style=" height: 20px;"><?= date('d/m/Y', $avoir->getAvoirDate()); ?></td>
                    <td><?= $avoir->getAvoirFactureId(); ?></td>
                </tr>
                <tr>
                    <td colspan="3" style="font-size: 13px;">
                        <span style="font-size:9px;">
                            <?= 'Téléphone: ' . $avoir->getAvoirClient()->getClientTel() . ' - Portable: ' . $avoir->getAvoirClient()->getClientPortable(); ?>
                        </span>
                        <br>
                        <br>
                        <?php
                        if ($avoir->getAvoirClient()->getClientType() == 1):
                            echo $avoir->getAvoirClient()->getClientNom() . ' ' . $avoir->getAvoirClient()->getClientPrenom() . '<br>';
                        else:
                            echo $avoir->getAvoirClient()->getClientRaisonSociale() . '<br>';
                        endif;
                        echo $avoir->getAvoirClient()->getClientAdresse1();
                        if ($avoir->getAvoirClient()->getClientAdresse2()):
                            echo '<br>' . $avoir->getAvoirClient()->getClientAdresse2();
                        endif;
                        echo '<br>' . $avoir->getAvoirClient()->getClientCp() . ' ' . $avoir->getAvoirClient()->getClientVille();
                        ?>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
<br>
<br>
<br>
<table class="table table-bordered" cellspacing="0" style="font-size:10px;">
    <tr style="background-color: #eae6e6; font-size:11px;">
        <td style="width:30px;">Ref</td>
        <td style="width:270px;">Description</td>
        <td style="text-align: right; width: 60px;">Qte</td>
        <td style="text-align: left; width: 30px;">(U)</td>
        <td style="text-align: right; width:60px;">
            PU HT
        </td>
        <td style="text-align: center; width:40px;"><!--Remise--></td>
        <td style="text-align: right; width:70px;">
            TOTAL HT
        </td>
    </tr>
    <?php
    if ($avoir->getAvoirLignes()):

        foreach ($avoir->getAvoirLignes() as $l):
            ?>
            <tr>
                <td><?php echo $l->getLigneProduitId(); ?></td>
                <td><?php echo $l->getLigneDesignation(); ?></td>
                <td style="text-align: right;"><?php echo number_format($l->getLigneQte(), 2, ',', ' '); ?></td>
                <td style="text-align: left;"><?php echo $this->cxwork->affUnite($l->getLigneUniteId()); ?></td>
                <td class="ligneTab" style="text-align: right;">
                    <?= number_format($l->getLignePrixUnitaire(), 2, ',', ' ');
                    ?>
                </td>
                <td class="ligneTab" style="text-align: center;">
                    <?php
                    if ($l->getLigneRemise() > 0 && $chiffrage):
                        echo '-' . number_format($l->getLigneArticle()->getArticleRemise(), 0, ',', ' ') . '%';
                    endif;
                    ?>
                </td>
                <td class="ligneTab" style="text-align: right;">
                    <?= number_format(round($l->getLignePrixNet() * $l->getLigneQte(), 2), 2, ',', ' ');
                    ?>
                </td>
            </tr>
            <?php
        endforeach;
    endif;
    ?>

</table>
<br>
<br>
<table style="font-size:11px;">
    <tr>
        <td rowspan="4" style="width: 200px;">

        </td>
        <td style="width:200px; text-align:right;">Total HT</td>
        <td style="text-align:right; width:130px; border-bottom:1px solid grey;"><?php echo '-' . number_format($avoir->getAvoirTotalHT(), 2, ',', ' ') . '€'; ?></td>
    </tr>
    <?php
    if (!empty($avoir->getAvoirTvas())):
        foreach ($avoir->getAvoirTvas() as $tva):
            ?>
            <tr>
                <td style="text-align:right;">TVA <?= $tva->getTvaTaux() . '%'; ?></td>
                <td style="text-align:right; border-bottom:1px solid grey;">
                    <?php
                    echo '-' . number_format($tva->getTvaMontant(), 2, ',', ' ') . '€';
                    ?>
                </td>
            </tr>
            <?php
        endforeach;
    else:
        if ($avoir->getAvoirTotalTVA() == 0):
            ?>
            <tr>
                <td style="text-align:right;" colspan="2">
                    Exonération TVA <?= $avoir->getAvoirClient()->getClientIntracom(); ?>
                </td>
            </tr>
            <?php
        endif;
    endif;
    ?>
    <tr>
        <td style="text-align:right; height:25px;">Total TTC</td>
        <td style="text-align:right; font-weight:bold;"><?php echo '-' . number_format($avoir->getAvoirTotalTTC(), 2, ',', ' ') . '€'; ?></td>
    </tr>
</table>
