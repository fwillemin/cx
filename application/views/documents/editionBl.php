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
                    <td colspan="2" style="text-align: center; font-weight: bold; height: 20px; font-size:15px;">
                        BON DE LIVRAISON
                    </td>
                </tr>
                <tr style="background-color: lightgrey; text-align: center; font-weight: bold;">
                    <td style="width: 150px;">N° livraison</td>
                    <td style="width: 150px;">Date</td>
                </tr>
                <tr style="text-align: center;">
                    <td style=" height: 20px;"><?= $bl->getBlId(); ?></td>
                    <td style=" height: 20px;"><?= date('d/m/Y', $bl->getBlDate()); ?></td>
                </tr>
                <tr>
                    <td colspan="2" style="font-size: 13px;">
                        <span style="font-size:9px;">
                            <?= 'Téléphone: ' . $client->getClientTel() . ' - Portable: ' . $client->getClientPortable(); ?>
                        </span>
                        <br>
                        <br>
                        <?php
                        if ($client->getClientType() == 1):
                            echo $client->getClientNom() . ' ' . $client->getClientPrenom() . '<span style="color:#FFF;">____</span><br>';
                        else:
                            echo $client->getClientRaisonSociale() . '<span style="color:#FFF;">____</span><br>';
                        endif;
                        echo $client->getClientAdresse1() . '<span style="color:#FFF;">____</span>';
                        if ($client->getClientAdresse2()):
                            echo '<br>' . $client->getClientAdresse2() . '<span style="color:#FFF;">____</span>';
                        endif;
                        echo '<br>' . $client->getClientCp() . ' ' . $client->getClientVille() . '<span style="color:#FFF;">____</span>';
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
    $totalBlHT = 0;
    $totalBlTVA = 0;
    if (!empty($bl->getBlLivraisons())):
        foreach ($bl->getBlLivraisons() as $l):
            ?>
            <tr>
                <td class="ligneTab" style="text-align: center;"><?= $l->getLivraisonArticle()->getArticleProduitId(); ?></td>
                <td class="ligneTab"><?= $l->getLivraisonArticle()->getArticleDesignation(); ?></td>
                <td class="ligneTab" style="text-align: right;"><?= number_format($l->getLivraisonQte(), 2, ',', ' '); ?></td>
                <td class="ligneTab" style="text-align: left;"><?= $this->cxwork->affUnite($l->getLivraisonArticle()->getArticleUniteId()); ?></td>
                <td class="ligneTab" style="text-align: right;">
                    <?php
                    if ($chiffrage):
                        echo number_format($l->getLivraisonArticle()->getArticlePrixUnitaire(), 2, ',', ' ');
                    endif;
                    ?>
                </td>
                <td class="ligneTab" style="text-align: center;">
                    <?php
                    if ($l->getLivraisonArticle()->getArticleRemise() > 0 && $chiffrage):
                        echo '-' . number_format($l->getLivraisonArticle()->getArticleRemise(), 0, ',', ' ') . '%';
                    endif;
                    ?>
                </td>
                <td class="ligneTab" style="text-align: right;">
                    <?php
                    $sstotal = round($l->getLivraisonArticle()->getArticlePrixNet() * $l->getLivraisonQte(), 2);
                    $totalBlHT += $sstotal;
                    if ($chiffrage):
                        echo number_format($sstotal, 2, ',', ' ');
                    endif;
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
<table>
    <tr>
        <td style="width: 200px;">
            <?php
            if ($this->session->userdata('venteCommentaire')):
                echo '<br><strong>Remarques : </strong><br>' . $this->session->userdata('venteCommentaire');
            endif;
            ?>
        </td>
        <td style="width: 350px;">
            <?php if ($chiffrage): ?>
                <table class="table" style="font-size:12px;">
                    <tr>
                        <td style="width:200px; text-align:right;">Total HT</td>
                        <td style="text-align:right; width:130px; border-bottom:1px solid grey;"><?php echo number_format($totalBlHT, 2, ',', ' ') . '€'; ?></td>
                    </tr>
                    <?php
                    if (!empty($bl->getBlTvas())):
                        foreach ($bl->getBlTvas() as $tva):
                            $totalBlTVA += $tva->getTvaMontant();
                            ?>
                            <tr>
                                <td style="text-align:right;">TVA <?= $tva->getTvaTaux() . '%'; ?></td>
                                <td style="text-align:right; border-bottom:1px solid grey;">
                                    <?php
                                    echo number_format($tva->getTvaMontant(), 2, ',', ' ') . '€';
                                    ?>
                                </td>
                            </tr>
                            <?php
                        endforeach;
                    elseif ($totalBlTVA == 0):
                        ?>
                        <tr>
                            <td style="text-align:right;" colspan="2">
                                Exonération TVA <?= $client->getClientIntracom(); ?>
                            </td>
                        </tr>
                        <?php
                    endif;
                    ?>
                    <tr>
                        <td style="text-align:right;">Total TTC</td>
                        <td style="text-align:right; border-bottom:1px solid grey;">
                            <?php
                            echo number_format($totalBlHT + $totalBlTVA, 2, ',', ' ') . '€';
                            ?>
                        </td>
                    </tr>
                </table>
            <?php endif; ?>
        </td>
    </tr>
    <tr style="font-size:10px;">
        <td colspan="2" style="text-align: center;">
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
<div style="width: 100%; text-align: center; color: orangered; background-color: #f8d2d2; padding:50px; border: 1px solid orangered;">
    <span style="font-weight: bold;">ATTENTION !!</span> Tous les produits livrés restent l'entière propriété de<br><strong><?= $pdv->getPdvRaisonSociale(); ?></strong><br>jusqu'à leur paiement intégral.
</div>