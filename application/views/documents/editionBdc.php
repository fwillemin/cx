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
                        BON DE COMMANDE
                    </td>
                </tr>
                <tr style="background-color: lightgrey; text-align: center; font-weight: bold;">
                    <td style="width: 90px;">N° commande</td>
                    <td style="width: 90px;">Date</td>
                    <td style="width: 90px;">Servi par</td>
                </tr>
                <tr style="text-align: center;">
                    <td style=" height: 20px;"><?= $bdc->getBdcId(); ?></td>
                    <td style=" height: 20px;"><?= date('d/m/Y', $bdc->getBdcDate()); ?></td>
                    <td><?= $bdc->getBdcCollaborateur()->getCollaborateurNom(); ?></td>
                </tr>
                <tr>
                    <td colspan="3" style="font-size: 13px;">
                        <span style="font-size:9px;">
                            <?= 'Téléphone: ' . $bdc->getBdcClient()->getClientTel() . ' - Portable: ' . $bdc->getBdcClient()->getClientPortable(); ?>
                        </span>
                        <br>
                        <br>
                        <?php
                        if ($bdc->getBdcClient()->getClientType() == 1):
                            echo $bdc->getBdcClient()->getClientNom() . ' ' . $bdc->getBdcClient()->getClientPrenom() . '<br>';
                        else:
                            echo $bdc->getBdcClient()->getClientRaisonSociale() . '<br>';
                        endif;
                        echo $bdc->getBdcClient()->getClientAdresse1();
                        if ($bdc->getBdcClient()->getClientAdresse2()):
                            echo '<br>' . $bdc->getBdcClient()->getClientAdresse2();
                        endif;
                        echo '<br>' . $bdc->getBdcClient()->getClientCp() . ' ' . $bdc->getBdcClient()->getClientVille();
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
<table class="table table-bordered" border="1" cellspacing="0">
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
    if ($bdc->getBdcArticles()):
        foreach ($bdc->getBdcArticles() as $l):
            ?>
            <tr>
                <td class="ligneTab" style="text-align: center;"><?= $l->getArticleProduitId(); ?></td>
                <td class="ligneTab"><?= $l->getArticleDesignation(); ?></td>
                <td class="ligneTab" style="text-align: right;"><?= number_format($l->getArticleQte(), 2, ',', ' '); ?></td>
                <td class="ligneTab" style="text-align: left;"><?= $this->cxwork->affUnite($l->getArticleUniteId()); ?></td>
                <td class="ligneTab" style="text-align: right;"><?php
                    $pu = $l->getArticlePrixUnitaire();
                    $sstotal = $l->getArticleTotalHT();
                    echo number_format($pu, 2, ',', ' ');
                    ?>
                </td>
                <td class="ligneTab" style="text-align: center;">
                    <?php
                    if ($l->getArticleRemise() > 0):
                        echo '-' . number_format($l->getArticleRemise(), 0, ',', ' ') . '%';
                    endif;
                    ?>
                </td>
                <td class="ligneTab" style="text-align: right;"><?= number_format($sstotal, 2, ',', ' '); ?></td>
            </tr>

            <?php
        endforeach;
    endif;
    ?>

</table>
<br>
<br>
<?php $nblignesTVA = count($bdc->getBdcTvas()); ?>
<table style="font-size:11px;">
    <tr>
        <td rowspan="<?= 2 + $nblignesTVA; ?>" style="font-size: 10px;"><?= '<strong>Commentaire : </strong>' . nl2br($bdc->getBdcCommentaire()); ?></td>
        <td style="width:200px; text-align:right;">Total HT</td>
        <td style="text-align:right; width:130px; border-bottom:1px solid grey;"><?php echo number_format(round($bdc->getBdcTotalHT(), 2), 2, ',', ' ') . '€'; ?></td>
    </tr>
    <?php
    if (!empty($bdc->getBdcTvas())):
        foreach ($bdc->getBdcTvas() as $tva):
            ?>
            <tr>
                <td style="width:200px; text-align:right;">TVA <?= $tva->getTvaTaux() . '%'; ?></td>
                <td style="text-align:right; width:130px; border-bottom:1px solid grey;">
                    <?php
                    echo number_format($tva->getTvaMontant(), 2, ',', ' ') . '€';
                    ?>
                </td>
            </tr>
            <?php
        endforeach;
    else:
        if ($bdc->getBdcTotalTVA() == 0):
            ?>
            <tr>
                <td style="text-align:right;" colspan="2">
                    Exonération TVA <?= $bdc->getBdcClient()->getClientIntracom(); ?>
                </td>
            </tr>
            <?php
        endif;
    endif;
    ?>
    <tr>
        <td style="width:200px; text-align:right;">Total TTC</td>
        <td style="text-align:right; width:130px; border-bottom:1px solid grey; font-weight:bold;">
            <?php echo number_format($bdc->getBdcTotalTTC(), 2, ',', ' ') . '€'; ?>
        </td>
    </tr>
</table>
<br>
<table>
    <?php
    if (!empty($acomptes)):
        echo '<tr><td colspan="3"><b>Acompte reçu :</b></td></tr>';
        foreach ($acomptes as $a):
            echo '<tr style="font-size:11px;"><td style="width: 90px;">Le ' . date('d/m/Y', $a->getAcompteDate()) . '</td><td style="width: 90px;">' . $a->getAcompteModeReglement()->getModeReglementNom() . '</td><td style="width: 90px;">' . number_format($a->getAcompteTotal(), 2, ',', ' ') . '€</td></tr>';
        endforeach;
    endif;
    ?>

    <tr style="font-size:12px;">
        <td colspan="3">
            <br>
            <br>Poids estimé de la commande : <strong><?php echo round($this->session->userdata('ventePoids'), 2) . ' Kg'; ?></strong>
            <br>Conditions de réglement : <?php echo $bdc->getBdcClient()->getClientConditionReglement()->getConditionReglementNom(); ?>
        </td>
    </tr>
    <tr style="font-size:10px;">
        <td colspan="3" style="text-align: center;">
            <br>
            <br>
            <br>
            <span style="font-size:10px;">
                Cachet, date et signature<br>précédés de "Bon pour accord"
            </span>
        </td>
    </tr>
</table>

<br>
<br>
<br>
<br>
<br>
<br>
<span style="font-size:10px;">
    <br>Sauf indication contraire dans ce bon de commande, le prix ne comprend pas la livraison.
    <br>Les produits sont à retirés dans notre entrepôt par vos propres moyens.
</span>
