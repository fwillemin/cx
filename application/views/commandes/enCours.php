<?php include('ssMenuCommande.php'); ?>
<div class="row baseCX" style="margin-top:5px;">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <h3>Commandes en cours</h3>
        <table class="table table-condensed" id="commandeAPasserTable">
            <thead>
            <th>N° Commande</th>
            <th>Usine</th>
            <th>Date</th>
            <th colspan="2">Etat</th>
            <th></th>
            </thead>
            <tbody>
                <?php
                if ($commandes):
                    foreach ($commandes as $c):
                        ?>
                        <tr class="ligneCommande" id="<?php echo $c->commandeId; ?>">
                            <td><?php echo $c->commandeId; ?></td>
                            <td><?php echo $c->usine; ?></td>
                            <td><?php echo date('d/m/Y', $c->commandeDate); ?></td>
                            <td colspan="2"><?php echo $this->cxwork->affCommandeEtat($c->commandeEtat); ?></td>
                            <td style="width:200px;">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $c->commandeAvancement; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $c->commandeAvancement; ?>%;"><?php echo $c->commandeAvancement; ?>%</div>
                                </div>
                            </td>
                        </tr>
                        <?php
                    endforeach;
                endif;
                ?>
            </tbody>
        </table>
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
                <button class="btn btn-gold btn-sm tooltipOk" title="Double click pour envoyer la commande à l'usine." data-placement="left" id="btnAddCommande" cible=""><i class="glyphicon glyphicon-shopping-cart"></i> Passer la commande</button>
            </div>
        </div>
    </div>
</div>
