<?php include('ssMenuFacturation.php'); ?>
<?php include('ssMenuPeriode.php'); ?>

<div class="container-fluid">
    <div class="row hidden-xs" style="margin-top:10px;">
        <div class="col-sm-4 col-sm-offset-2 baseCX">
            <a href="<?= site_url('facturation/remisesDeCheques/' . $debut . '/' . $fin); ?>" target="_blank" class="btn btn-default pull-right">
                <i class="glyphicon glyphicon-print"></i>
            </a>
            <h2>Remises de chèques</h2>        
            <?php if (!empty($cheques)): ?>

                <table class="table table-condensed table-striped" id="tableCheques">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Facture</th>
                            <th style="text-align: right;">Montant</th>
                            <th style="text-align: center;">Remise de Chèque</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cheques as $c): ?>
                            <tr>
                                <td width="80"><?php echo date('d/m/Y', $c->getReglementDate()); ?></td>
                                <td><?php echo $c->getReglementclient()->getClientNom(); ?></td>
                                <td><?php echo 'Facture ' . $c->getReglementFactureId(); ?></td>
                                <td style="text-align: right;"><?php echo number_format($c->getReglementTotal(), 2, '.', ' '); ?></td>
                                <td style="text-align: center; width:180px;">
                                    <?php
                                    if ($c->getReglementRemiseId() == 0): /* on donne la possibilité d'inclure ce cheque dans une remise */
                                        echo '<input type="checkbox" class="addRemiseCheque" name="addRemiseCheque" value="' . $c->getReglementId() . '" >';
                                    else:
                                        echo '<button class="btn btn-link btn-xs btnViewRemise" cible="' . $c->getReglementRemiseId() . '">Remise ' . $c->getReglementRemiseId() . ' du ' . date('d/m/Y', $c->getReglementRemise()->getRemiseDate()) . '</button>';
                                    endif;
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>        
                <?php
            else: echo 'Aucun chèque sur cette période.';
            endif;
            ?>

        </div>

        <div class="col-sm-3">

            <div class="row" style="margin-left:10px; background-color: #FFF; border:1px solid black; padding: 5px;">
                <div class="col-sm-12">
                    <h3 id="titreActionCaisse">Ajouter une remise de chèques</h3>
                    <?php echo form_open('facturation/addRemiseCheque', array('class', 'form-horizontal', 'id' => 'formAddRemiseCheque')); ?>

                    <div class="form-group">
                        <label for="addRemiseDate" class="col-sm-4">Date</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control input-sm" name="addRemiseDate" id="addRemiseDate" value="<?php echo date('Y-m-d'); ?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="addRemiseBanque" class="col-sm-4">Banque</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control input-sm" name="addRemiseBanque" id="addRemiseBanque" value="" >
                        </div>
                    </div>
                    <br>
                    <br>
                    <br>
                    <div class="form-group" style="margin-top:10px;">
                        <h5 style="text-decoration: underline;">Chèque inclus dans la remise :</h5>
                        <table id="listeChequeARemettre" class="table table-bordered table-condensed table-striped" style="font-size:11px; margin:15px; width:90%;">

                        </table>
                        <h4>Total de la remise <span style="font-weight:bold;" id="totalRemise">0</span>€</h4>
                    </div>
                    <button class="btn btn-warning col-sm-3" type="submit" id="btnSubmitAddMouvementCaisse" style="width: 100%;"><i class="glyphicon glyphicon-piggy-bank"></i> Remettre en banque</button>            
                    <?php echo form_close(); ?>        
                    <button class="btn btn-xs btn-link col-sm-3" id="btnAbortAddMouvementCaisse" style="display:none; position: relative; top:20px;">Annuler</button>
                    <button class="btn btn-danger btn-xs tooltipOk pull-right" data-placement="left" title="Double-click pour supprimer" id="btnDelCaisse" cible="" style="display:none; position: relative; top:20px;"><i class="glyphicon glyphicon-erase"></i> Supprimer</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRemise" tabindex="-1" role="dialog" aria-labelledby="Visualisation d'une remise de chèque" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #000; color: #FFF;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="glyphicon glyphicon-remove" style="color:#f50a1c;"> </i></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <h4>Chèques inclus dans le remise</h4>
                <table class="table table-condensed table-striped" id="tableViewRemise">

                </table>
                <button class="btn btn-success btn-sm pull-right" id="viewRemiseTotal"></button>
                <br>
            </div>
            <div class="modal-footer">                
                <button class="btn btn-danger btn-xs tooltipOk pull-left" title="Double-click pour supprimer la remise de chèques" data-placement="left" id="btnDelRemise"><i class="glyphicon glyphicon-erase"></i> Supprimer</button>
            </div>
        </div>
    </div>
</div>