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
                        FACTURE
                    </td>
                </tr>
                <tr style="background-color: lightgrey; text-align: center; font-weight: bold;">
                    <td style="width: 100px;">N° facture</td>
                    <td style="width: 100px;">Date</td>
                    <td style="width: 100px;">Echéance</td>
                </tr>
                <tr style="text-align: center;">
                    <td style=" height: 20px;"><?= $facture->getFactureId(); ?></td>
                    <td style=" height: 20px;"><?= date('d/m/Y', $facture->getFactureDate()); ?></td>
                    <td><?= date('d/m/Y', $facture->getFactureEcheance()); ?></td>
                </tr>
                <tr>
                    <td colspan="3" style="font-size: 12px;">
                        <span style="font-size:9px;">
                            <?= 'Téléphone: ' . $facture->getFactureClient()->getClientTel() . ' - Portable: ' . $facture->getFactureClient()->getClientPortable(); ?>
                        </span>
                        <br>
                        <br>
                        <br>
                        <?php
                        if ($facture->getFactureClient()->getClientType() == 1):
                            echo $facture->getFactureClient()->getClientNom() . ' ' . $facture->getFactureClient()->getClientPrenom() . '<span style="color:#FFF;">____</span><br>';
                        else:
                            echo $facture->getFactureClient()->getClientRaisonSociale() . '<span style="color:#FFF;">____</span><br>';
                        endif;
                        echo $facture->getFactureClient()->getClientAdresse1() . '<span style="color:#FFF;">____</span>';
                        if ($facture->getFactureClient()->getClientAdresse2()):
                            echo '<br>' . $facture->getFactureClient()->getClientAdresse2() . '<span style="color:#FFF;">____</span>';
                        endif;
                        echo '<br>' . $facture->getFactureClient()->getClientCp() . ' ' . $facture->getFactureClient()->getClientVille() . '<span style="color:#FFF;">____</span>';
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
    if ($facture->getFactureLignes()):
        $bl = 0;
        foreach ($facture->getFactureLignes() as $l):
            if ($l->getLigneBlId() != $bl):
//                if ($bl > 0 && $l->commentaire != '')
//                    echo '<tr><td></td><td colspan="6" style="font-size:8px;"><em>Commentaire : ' . $l->commentaire . '</em></td></tr>';
                echo '<tr><td colspan="7" style="border-bottom:1px solid grey;"><em>Bon de livraison N°' . $l->getLigneBlId() . '</em></td></tr>';
                $bl = $l->getLigneBlId();
            endif;
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
                    if ($l->getLigneRemise() > 0):
                        echo '-' . number_format($l->getLigneRemise(), 0, ',', ' ') . '%';
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
            <table class="table table-bordered" style="font-size:10px;">
                <tr>
                    <td colspan="3" style="border-bottom: 1px solid gray;">
                        Réglements encaissés
                    </td>
                </tr>
                <?php
                if (!empty($facture->getFactureReglements())):
                    foreach ($facture->getFactureReglements() as $r)
                        echo '<tr><td>' . date('d/m/Y', $r->getReglementDate()) . '</td><td>' . $this->cxwork->affModeReglement($r->getReglementModeId()) . '</td><td>' . number_format($r->getReglementMontant(), 2, ',', ' ') . '€</td></tr>';
                endif;
                ?>
            </table>
        </td>
        <td style="width:200px; text-align:right;">Total HT</td>
        <td style="text-align:right; width:130px; border-bottom:1px solid grey;"><?php echo number_format($facture->getFactureTotalHT(), 2, ',', ' ') . '€'; ?></td>
    </tr>
    <?php
    if (!empty($facture->getFactureTvas())):
        foreach ($facture->getFactureTvas() as $tva):
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
    else:
        if ($facture->getFactureTotalTVA() == 0):
            ?>
            <tr>
                <td style="text-align:right;" colspan="2">
                    Exonération TVA <?= $facture->getFactureClient()->getClientIntracom(); ?>
                </td>
            </tr>
            <?php
        endif;
    endif;
    ?>
    <tr>
        <td style="text-align:right; height:25px;">Total TTC</td>
        <td style="text-align:right; border-bottom:1px solid grey; font-weight:bold;"><?php echo number_format($facture->getFactureTotalTTC(), 2, ',', ' ') . '€'; ?></td>
    </tr>
    <tr style="font-size: 15px;">
        <td style="text-align:right;">Reste à régler</td>
        <td style="text-align:right; border-bottom:1px solid grey; font-weight: bold;"><?php echo number_format($facture->getFactureSolde(), 2, ',', ' ') . '€'; ?></td>
    </tr>
</table>
<br>
<br>
<br>
<span style="font-size:10px;">
    <?php
    if ($facture->getFactureEcheance() > 0):
        $echeance = date('d/m/Y', $facture->getFactureEcheance());
    else:
        $echeance = '';
    endif;
    echo '<br>Date d\'échéance : ' . $echeance
    . '<br>Conditions de réglement : ' . $facture->getFactureClient()->getClientConditionReglement()->getConditionReglementNom();
    //echo '<br>Mode de réglement : ' . $facture->getFactureClient()->getClientModeReglement()->getModeReglementNom();
    ?>
    <br>
    <br><strong>RESERVE DE PROPRIETE :</strong> En application de la loi 80335 du 12 mai 1980, les marchandises restent la
    propriété du vendeur jusqu'au paiement intégral de leur prix. Les risques afférents aux dites
    marchandises sont transférés à l'acheteur dés la livraison.
    <br><strong>DEFAILLANCE DU DEBITEUR :</strong> Indemnité sera de 15% sur les sommes dues conformement aux articles
    1226 et 1152 du code civil.
</span>