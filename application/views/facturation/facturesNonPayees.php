<?php include('ssMenuFacturation.php'); ?>
<div class="container-fluid">
    <div class="row hidden-xs" id="">

        <div class="baseCX col-sm-8 col-sm-offset-2">

            <table class="table table-condensed" id="tableNonPayees">
                <thead>
                <th>N°</th>
                <th>Date</th>
                <th>Bdc associés</th>
                <th>Client</th>
                <th style="text-align: right;">Total</th>
                <th style="text-align: right;">Solde</th>
                <th width="50"></th>
                </thead>
                <?php
                $totalNonPayees = 0;
                if (!empty($factures)):
                    foreach ($factures as $f):
                        $totalNonPayees += $f->getFactureSolde();
                        ?>
                        <tr>
                            <td>
                                <a href="<?php echo site_url('factures/ficheFacture/' . $f->getFactureId()); ?>" target="_blank">
                                    <?php
                                    if ($f->getFactureTotalHT() > 0):
                                        echo 'FA ';
                                    else:
                                        echo 'AVOIR ';
                                    endif;
                                    echo $f->getFactureId();
                                    ?>
                                </a>
                            </td>
                            <td><?php echo date('d/m/Y', $f->getFactureDate()); ?></td>
                            <td>
                                <?php
                                if (!empty($f->getFactureBls())):
                                    foreach ($f->getFactureBls() as $b):
                                        echo '<a href="' . site_url('ventes/reloadBdc/' . $b->getBlBdcId()) . '" target="_blank">' . $b->getBlBdcId() . '</a>, ';
                                    endforeach;
                                elseif ($f->getFactureDelete() == 1):
                                    echo '<span class="label label-danger">Facture supprimée</span>';
                                endif;
                                ?>
                            </td>
                            <td>
                                <?php
                                $client = $f->getFactureClient();
                                if ($client->getClientType() == 1):
                                    echo $client->getClientNom() . ' ' . $client->getClientPrenom();
                                else:
                                    echo $client->getClientRaisonSociale();
                                endif;
                                ?>
                            </td>
                            <td style="text-align: right;"><?php echo number_format(round($f->getFactureTotalTTC(), 2), 2, ',', ' ') . '€'; ?></td>
                            <td style="text-align: right;"><?php echo number_format($f->getFactureSolde(), 2, ',', ' ') . '€'; ?></td>
                            <td style="text-align: right;">
                                <?php if ($f->getFactureSolde() == $f->getFactureTotalTTC() && $f->getFactureDelete() == 0): ?>
                                    <i class="glyphicon glyphicon-erase tooltipOk btnDelFactureAsk" data-placement="left" title="Double-click pour supprimer cette facture" cible="<?php echo $f->getFactureId(); ?>"></i>
                                <?php endif;
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="5" style="text-align: right; font-size:15px; font-weight:bold;">Total</td>
                        <td style="text-align: right; font-size:15px; font-weight:bold;"><?php echo number_format($totalNonPayees, 2, ',', ' ') . '€'; ?></td>
                        <td></td>
                    </tr>
                <?php endif;
                ?>
            </table>

        </div>
    </div>
</div>

<div class="modal fade" id="modalDelFacture" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fas fa-trash-o"></i> Suppression d'une facture</h4>
            </div>
            <div class="modal-body alert alert-danger">
                <i class="glyphicon glyphicon-alert"></i><br>
                Vous allez supprimer une facture ce qui va entraîner :
                <ul>
                    <li>la suppression de tous les réglements,</li>
                    <li>les acomptes utilisés seront libérés</li>
                    <li>les bons de livraison facturés seront reinitialisés</li>
                </ul>
                <br><strong>Cette opération n'est pas réversible !!</strong>
                <br>Que souhaitez-vous faire ?
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-xs-6">
                        <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">
                            <i class="glyphicon glyphicon-ban-circle" style="color:green;"></i> Ne rien supprimer et annuler
                        </button>
                    </div>
                    <div class="col-xs-6">
                        <button type="button" class="btn btn-danger" id="btnDelFacture" cible="">
                            <i class="glyphicon glyphicon-alert"></i> Je comprend et<br>je supprime la facture
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
