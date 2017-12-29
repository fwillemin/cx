<?php include('ssMenuCommande.php'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="baseCX col-sm-8 col-sm-offset-2">
            <h3>Commandes à passer</h3>
            <table class="table table-condensed" id="commandeAPasserTable">
                <thead>
                <th>Appro</th>
                <th>Désignation</th>
                <th style="width:50px; text-align: center;"><img src="<?php echo base_url('assets/img/icon/palette.png'); ?>" style="height:12px;" /></th>
                <th style="width:100px; text-align: center;">Qte</th>
                <th style="text-align: center;">Unité</th>
                <th>PA</th>
                <th style="text-align: right;">Total</th>
                </thead>
                <tbody>
                    <?php
                    if (!empty($commandesappro)):
                        $usine = 0;
                        $totalUsine = 0;
                        foreach ($commandesappro as $a):
                            if ($usine != $a->usineId):
                                /* on affiche une ligne de totaliation pour l'usine en cours */
                                if ($usine > 0):
                                    ?>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td colspan="3" style="text-align: center;"><button class="btn btn-info btnCommandeVisualisation" cible="<?php echo $usine; ?>">Visualiser la commande</button></td>
                                        <td style="text-align: right;"><?php echo number_format($totalUsine, 2, ',', ' ') . '€'; ?></td>
                                    </tr>
                                    <?php
                                    $totalUsine = 0;
                                endif;
                                echo ('<tr><td colspan="7" class="alert alert-warning">' . $a->usine . '</td></tr>');
                                $usine = $a->usineId;
                            endif;
                            /* ligne d'appro */
                            ?>
                            <tr>
                                <td><?php echo $a->approId; ?></td>
                                <td><?php echo $a->produitDesignation; ?></td>
                                <td style="width:50px; text-align: center;">
                                    <?php
                                    if ($a->approQte >= $a->produitSeuilPalette)
                                        echo '<img src="' . base_url('assets/img/icon/palette.png') . '" style="height:12px;" /> ';
                                    else
                                        echo '<span style="font-size:10px;">Min' . $a->produitSeuilPalette . '</span>';
                                    ?>
                                </td>
                                <td>
                                    <input type="text" name="commandeApproModQte" class="form-control commandeApproModQte input-sm form-control-blank-right" style="width:100px;" value="<?php echo $a->approQte; ?>" cible="<?php echo $a->approId; ?>"/>
                                </td>
                                <td style="text-align: center;"><?php echo $this->cxwork->affUnite($a->produitUniteId); ?></td>
                                <td><?php
                                    /* recherche du prix d'achat */
                                    if ($a->approQte >= $a->produitSeuilPalette)
                                        $PA = $a->produitPrixAchatPalette;
                                    else
                                        $PA = $a->produitPrixAchatUnitaire;
                                    echo number_format($PA, 2, ',', ' ') . '€';
                                    ?>
                                </td>
                                <td style="text-align: right;"><?php echo number_format(round($a->approQte * $PA, 2), 2, ',', ' ') . '€' ?></td>
                            </tr>
                            <?php
                            $totalUsine += $a->approQte * $PA;
                            /* liste des BDC liés à cette ligne d'appro */
                            if ($bdcarticle):
                                $qteACommander = 0;
                                foreach ($bdcarticle as $b):
                                    if ($b->articleApproId == $a->approId): $qteACommander += $b->articleQte;
                                        ?>
                                        <tr class="smallLigne">
                                            <td></td>
                                            <td colspan="2">
                                                <a href="<?php echo site_url('ventes/reloadBdc/' . $b->articleBdcId); ?>" target="_blank">
                                                    <?php echo 'Bon de commande N°' . $b->bdc . ' pour le client ' . $b->clientNom; ?>
                                                </a>
                                            </td>
                                            <td><?php echo $b->articleQte; ?></td>
                                            <td style="text-align: center;"><?php echo $this->cxwork->affUnite($b->articleUniteId); ?></td>
                                            <td colspan="2"></td>
                                        </tr>
                                        <?php
                                    endif;
                                endforeach;
                                if ($qteACommander > $a->approQte && abs($qteACommander - $a->approQte) > 0.009):
                                    echo '<tr class="smallLigne"><td></td><td colspan="5"><span style="color:red;">Quantités incohérentes</span></td></tr>';
                                elseif ($qteACommander < $a->approQte && abs($qteACommander - $a->approQte) > 0.009):
                                    ?>
                                    <tr class="smallLigne" style="color:green;">
                                        <td></td>
                                        <td>Pour stock</td>
                                        <td><?php echo $a->approQte - $qteACommander; ?></td>
                                        <td style="text-align: center;"><?php echo $this->cxwork->affUnite($a->produitUniteId); ?></td>
                                        <td colspan="2"></td>
                                    </tr>
                                    <?php
                                endif;
                            endif;

                        endforeach;
                        ?>
                        <tr>
                            <td colspan="3"></td>
                            <td colspan="3" style="text-align: center;"><button class="btn btn-info btnCommandeVisualisation" cible="<?php echo $usine; ?>">Visualiser la commande</button></td>
                            <td style="text-align: right;"><?php echo number_format($totalUsine, 2, ',', ' ') . '€'; ?></td>
                        </tr>
                    <?php endif;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- message générer pour une commande ----------------------------- -->
<div class="modal fade" id="modalCommandeVisualisation" tabindex="-1" role="dialog" aria-labelledby="Ajouter un produit" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="glyphicon glyphicon-remove" style="color:#f50a1c;"> </i></button>
                <h4 class="modal-title"><strong>Prévisualisation d'une commande usine</strong></h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button class="btn btn-gold btn-sm tooltipOk" title="Double click pour générer le bon de commande." data-placement="left" id="btnAddCommande" cible=""><i class="glyphicon glyphicon-shopping-cart"></i> Générer le bon de commande</button>
            </div>
        </div>
    </div>
</div>
