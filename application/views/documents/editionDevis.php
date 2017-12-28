<table>
    <tr style="font-size:10px;">
        <td style="width: 240px;">
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

            <table style="width:300px;" cellspacing="0" border="1" cellpadding="2">
                <tr>
                    <td colspan="3" style="text-align: center; font-weight: bold; height: 20px; font-size:15px;">
                        DEVIS
                    </td>
                </tr>
                <tr style="background-color: lightgrey; text-align: center; font-weight: bold;">
                    <td style="width: 100px;">N° devis</td>
                    <td style="width: 100px;">Date</td>
                    <td style="width: 100px;">Servi par</td>
                </tr>
                <tr style="text-align: center;">
                    <td style=" height: 20px;"><?= $devis->getDevisId(); ?></td>
                    <td style=" height: 20px;"><?= date('d/m/Y', $devis->getDevisDate()); ?></td>
                    <td><?= $devis->getDevisCollaborateur()->getCollaborateurNom(); ?></td>
                </tr>
                <tr>
                    <td colspan="3" style="font-size: 13px;">
                        <span style="font-size:9px;">
                            <?= 'Téléphone: ' . $devis->getDevisClient()->getClientTel() . ' - Portable: ' . $devis->getDevisClient()->getClientPortable(); ?>
                        </span>
                        <br>
                        <br>
                        <?php
                        if ($devis->getDevisClient()->getClientType() == 1):
                            echo $devis->getDevisClient()->getClientNom() . ' ' . $devis->getDevisClient()->getClientPrenom() . '<span style="color:#FFF;">____</span><br>';
                        else:
                            echo $devis->getDevisClient()->getClientRaisonSociale() . '<span style="color:#FFF;">____</span><br>';
                        endif;
                        echo $devis->getDevisClient()->getClientAdresse1() . '<span style="color:#FFF;">____</span>';
                        if ($devis->getDevisClient()->getClientAdresse2()):
                            echo '<br>' . $devis->getDevisClient()->getClientAdresse2() . '<span style="color:#FFF;">____</span>';
                        endif;
                        echo '<br>' . $devis->getDevisClient()->getClientCp() . ' ' . $devis->getDevisClient()->getClientVille() . '<span style="color:#FFF;">____</span>';
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
    if ($articles):
        foreach ($articles as $l):
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
<table style="font-size:11px;">
    <tr>
        <td style="width: 200px;" rowspan="3">
            <br>Poids estimé : <strong><?= round($this->session->userdata('ventePoids'), 2) . ' Kg'; ?></strong>
            <br>Conditions de réglement : <?= $devis->getDevisClient()->getClientConditionReglement()->getConditionReglementNom(); ?>
        </td>
        <td style="width:200px; text-align:right;">Total HT</td>
        <td style="text-align:right; width:130px; border-bottom:1px solid grey;"><?= number_format(round($devis->getDevisTotalHT(), 2), 2, ', ', ' ') . '€';
    ?></td>
    </tr>
    <?php
    if (!empty($devis->getDevisTvas())):
        foreach ($devis->getDevisTvas() as $tva):
            ?>
            <tr>
                <td style="text-align:right;">TVA <?= $tva->getTvaTaux() . '%'; ?></td>
                <td style="text-align:right; width:130px; border-bottom:1px solid grey;">
                    <?php
                    echo number_format($tva->getTvaMontant(), 2, ',', ' ') . '€';
                    ?>
                </td>
            </tr>
            <?php
        endforeach;
    else:
        if ($devis->getDevisTotalTVA() == 0):
            ?>
            <tr>
                <td style="text-align:right;" colspan="2">
                    Exonération TVA <?= $devis->getDevisClient()->getClientIntracom(); ?>
                </td>
            </tr>
            <?php
        endif;
    endif;
    ?>
    <tr>
        <td style="text-align:right;">Total TTC</td>
        <td style="text-align:right; width:130px; border-bottom:1px solid grey; font-weight:bold;">
            <?= number_format($devis->getDevisTotalTTC(), 2, ',', ' ') . '€'; ?>
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
    <br>Ce devis est valable 30 jours. Pour toute commande, il sera demandé un acompte de 30%.
    <br>Sauf indication contraire dans ce devis, le prix ne comprend pas la livraison.
    <br>Les produits sont à retirés dans notre entrepôt par vos propres moyens.
</span>
