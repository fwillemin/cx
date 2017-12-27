<div class="container">
    <?php include('ssMenuProduit.php'); ?>
    <div class="row">
        <div class="col-xs-12 baseCX">
            <button id="btnAddUsine" class="btn btn-warning btn-sm pull-right"><i class="glyphicon glyphicon-plus"></i> Ajouter une usine</button>
            <table class="table table-hover table-condensed" id="usinesTable">
                <thead>
                <th>Usine</th>
                <th>Code client</th>
                <th>Email</th>
                </thead>
                <tbody>
                    <?php
                    if (!empty($usines)):
                        foreach ($usines as $u):
                            ?>
                            <tr id="<?php echo $u->getUsineId(); ?>" class="usinesLigne">
                                <td><?php echo $u->getUsineNom(); ?></td>
                                <td><?php echo $u->getUsineCodeClient(); ?></td>
                                <td><?php echo $u->getUsineEmail(); ?></td>
                                <td><button class="btn btn-xs btn-link btnModUsine" data-usineid="<?php echo $u->getUsineId(); ?>"><i class="fas fa-pencil-alt"></i> </button></td>
                            </tr>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Ajout d'une usine ------------------------------------------------------------------------------------------------------ -->
<div class="modal fade" id="modalAddUsine" tabindex="-1" role="dialog" aria-labelledby="Ajouter une usine" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="glyphicon glyphicon-remove" style="color:#f50a1c;"> </i></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('produits/addUsine/', array('class' => 'form-horizontal', 'id' => 'formAddUsine')); ?>
                <input type="hidden" name="addUsineId" id="addUsineId" value="" >

                <div class="form-group has-error">
                    <label for="addUsine" class="col-sm-3 control-label">Usine <span class="asterix">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" id="addUsineNom" class="form-control input-sm requiredField" name="addUsineNom" required value="" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="addUsineEmail" class="col-sm-3 control-label">Email</label>
                    <div class="col-sm-9">
                        <input type="text" id="addUsineEmail" class="form-control input-sm" name="addUsineEmail" value="" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="addUsineCodeClient" class="col-sm-3 control-label">Code client</label>
                    <div class="col-sm-4">
                        <input type="text" id="addUsineCodeClient" class="form-control input-sm" name="addUsineCodeClient" value="" >
                    </div>
                </div>

                <button class="btn btn-warning btn-sm" style="width:100%;" type="submit" id="btnAddUsineSubmit"></button>
                <?php echo form_close(); ?>
                <hr>
                <button type="button" class="btn btn-danger btn-xs tooltipOk" title="Double-click pour supprimer cette usine" data-placement="right" type="submit" id="btnDelUsine" data-usineid=""><i class="glyphicon glyphicon-erase"></i> </button>
            </div>
        </div>
    </div>
</div>


