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
            <?php $client = $bdc->getBdcClient(); ?>
            <h1><?php echo 'BON DE COMMANDE N°' . $bdc->getBdcId(); ?></h1>
            Le <?php echo date('d/m/Y', $bdc->getBdcDate()); ?>
            <br>Servi par <?php echo $bdc->getBdcUser()->getUserNom(); ?><br>
            <?php
            if ($client->getClientTel() != ''):
                echo 'Téléphone client : ' . $client->getClientTel();
            endif;
            if ($client->getClientPortable() != ''):
                echo 'Portable : ' . $client->getClientPortable();
            endif;
            ?>

            <div style="">
                <br><p>
                    <?php
                    if ($client->getClientType() == 1):
                        echo $client->getClientNom() . ' ' . $client->getClientPrenom() . '<span style="color:#FFF;">____________</span><br>';
                    else:
                        echo $client->getClientRaisonSociale() . '<span style="color:#FFF;">____________</span><br>';
                    endif;
                    echo $client->getClientAdresse1() . '<span style="color:#FFF;">____________</span>';
                    if ($client->getClientAdresse2()):
                        echo '<br>' . $client->getClientAdresse2() . '<span style="color:#FFF;">____________</span>';
                    endif;
                    echo '<br>' . $client->getClientCp() . ' ' . $client->getClientVille() . '<span style="color:#FFF;">____________</span>';
                    ?>
                </p>
            </div>
        </td>
    </tr>
</table>
<br>
<table class="table table-bordered" cellspacing="5" style="font-size:9px;">
    <tr style="background-color: #eae6e6;">
        <td style="width:30px;">Ref</td>
        <td style="width:230px;">Description</td>
        <td style="text-align: center; width:50px;">Qte</td>
        <td style="text-align: center; width:30px;">Unite</td>
        <td style="text-align: center; width:60px;">
            PU <?php
            if ($client->getClientType() == 1):
                echo 'TTC';
            else:
                echo 'HT';
            endif;
            ?>
        </td>
        <td style="text-align: center; width:40px;"><!--Remise--></td>
        <td style="text-align: center; width:80px;">
            TOTAL <?php
            if ($client->getClientType() == 1):
                echo 'TTC';
            else:
                echo 'HT';
            endif;
            ?>
        </td>
    </tr>
    <?php
    if ($bdc->getBdcArticles()):
        foreach ($bdc->getBdcArticles() as $l):
            ?>
            <tr>
                <td><?php echo $l->getArticleProduitId(); ?></td>
                <td><?php echo $l->getArticleDesignation(); ?></td>
                <td style="text-align: center;"><?php echo number_format($l->getArticleQte(), 2, ',', ' '); ?></td>
                <td style="text-align: center;"><?php echo $this->cxwork->affUnite($l->getArticleUniteId()); ?></td>
                <td style="text-align: center;"><?php
                    if ($client->getClientType() == 1):
                        $pu = round($l->getArticlePrixUnitaire() * (1 + $l->getArticleTauxTVA() / 100), 2);
                        $sstotal = $l->getArticleTotalTTC();
                    else:
                        $pu = $l->getArticlePrixUnitaire();
                        $sstotal = $l->getArticleTotalHT();
                    endif;
                    echo number_format($pu, 2, ',', ' ');
                    ?>
                </td>
                <td style="text-align: center;">
                    <?php
                    if ($l->getArticleRemise() > 0):
                        echo number_format($l->getArticleRemise(), 0, ',', ' ') . '%';
                    endif;
                    ?>
                </td>
                <td style="text-align: right;"><?php echo number_format($sstotal, 2, ',', ' '); ?></td>
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
            <br>
            Poids estimé de la commande : <strong><?php echo round($this->session->userdata('ventePoids'), 2) . ' Kg'; ?></strong>
        </td>
    </tr>
    <tr style="font-size:12px;">
        <td colspan="3">
            Conditions de réglement : <?php echo $client->getClientConditionReglement()->getConditionReglementNom(); ?>
        </td>
    </tr>
</table>

<br>
<br>
<br>
<span style="font-size:10px;">
    <br>Sauf indication contraire dans ce bon de commande, le prix ne comprend pas la livraison.
    <br>Les produits sont à retirés dans notre entrepôt par vos propres moyens.
    <br>Visitez notre nouveau site internet www.carreauximportnegoce.fr
</span>
