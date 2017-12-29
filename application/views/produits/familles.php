<div class="container">
    <?php include('ssMenuProduit.php'); ?>
    <div class="row">
        <div class="col-xs-12 baseCX">
            <button id="btnAddFamille" class="btn btn-warning btn-sm pull-right"><i class="glyphicon glyphicon-plus"></i> Ajouter une famille</button>
            <table class="table table-hover table-condensed" id="famillesTable">
                <thead>
                <th></th>
                <th style="width:20px;"></th>
                </thead>
                <tbody>
                    <?php
                    if (!empty($familles)):
                        foreach ($familles as $f):
                            ?>
                            <tr id="<?php echo $f->getFamilleId(); ?>" class="famillesLigne">
                                <td>
                                    <?php echo $f->getFamilleNom(); ?>
                                </td>
                                <td><button class="btn btn-xs btn-link btnModFamille" data-familleid="<?php echo $f->getFamilleId(); ?>"><i class="fas fa-pencil-alt"></i> </button></td>
                            </tr>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Ajout d'une famille ------------------------------------------------------------------------------------------------------ -->
    <div class="modal fade" id="modalAddFamille" tabindex="-1" role="dialog" aria-labelledby="Ajouter une famille" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="glyphicon glyphicon-remove" style="color:#f50a1c;"> </i></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <?php echo form_open('produits/addFamille/', array('class' => 'form-horizontal', 'id' => 'formAddFamille')); ?>
                    <input type="hidden" name="addFamilleId" id="addFamilleId" value="" >

                    <div class="form-group has-error">
                        <label for="addFamille" class="col-lg-3 col-md-3 col-sm-3 control-label">Famille</label>
                        <div class="col-lg-9 col-md-9 col-sm-9">
                            <input type="text" id="addFamilleNom" class="form-control input-sm requiredField" name="addFamilleNom" required value="" placeholder="Nom" >
                        </div>
                    </div>

                    <button class="btn btn-warning btn-sm" type="submit" id="btnAddFamilleSubmit" style="width:100%;"></button>
                    <?php echo form_close(); ?>
                    <hr>
                    <button type="button" class="btn btn-danger btn-xs tooltipOk" title="Double-click pour supprimer cette famille" data-placement="bottom" type="submit" id="btnDelFamille" data-familleid=""><i class="glyphicon glyphicon-erase"></i> </button>

                </div>
            </div>
        </div>
    </div>
</div>


