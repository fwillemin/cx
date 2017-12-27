<div class="modal fade" id="modalAddProduit" tabindex="-1" role="dialog" aria-labelledby="Ajouter un produit" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="glyphicon glyphicon-remove" style="color:#f50a1c;"> </i></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('produits/addproduit', array('class' => 'form-horizontal', 'id' => 'formAddProduit')); ?>
                <input type="hidden" name="addProduitId" id="addProduitId" value="<?php if( !empty($produit)) echo $produit->getProduitId(); ?>" >                

                <div class="form-group">
                    <label for="addProduitRefUsine" class="col-xs-3 control-label">Réf usine</label>
                    <div class="col-xs-9">
                        <input type="text" id="addProduitRefUsine" class="form-control input-sm" name="addProduitRefUsine" value="<?php if( !empty($produit)) echo $produit->getProduitRefUsine(); ?>" >
                    </div>
                </div>
                <div class="form-group has-error">
                    <label for="addProduitDesignation" class="col-xs-3 control-label">Désignation <span class="asterix">*</span></label>
                    <div class="col-xs-9">
                        <input type="text" id="addProduitDesignation" class="form-control input-sm requiredField" name="addProduitDesignation" required value="<?php if( !empty($produit)) echo $produit->getProduitDesignation(); ?>" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="addProduitEAN" class="col-xs-3 control-label">Code EAN</label>
                    <div class="col-xs-9">
                        <input type="text" id="addProduitEAN" class="form-control input-sm" name="addProduitEAN" value="<?php if( !empty($produit)) echo $produit->getProduitEAN(); ?>" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="addProduitUsine" class="col-xs-3 control-label">Usine</label>
                    <div class="col-xs-9">
                        <select name="addProduitUsine" id="addProduitUsine" class="form-control">
                            <?php
                            if (!empty($usines)):
                                foreach ($usines as $u):
                                    ?>
                                    <option value="<?php echo $u->getUsineId(); ?>" <?php if( !empty($produit) && $produit->getProduitUsineId() == $u->getUsineId() ) echo 'selected'; ?>><?php echo $u->getUsineNom(); ?></option>
                                    <?php
                                endforeach;
                            endif;
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="addProduitFamille" class="col-xs-3 control-label">Famille</label>
                    <div class="col-xs-9">
                        <select name="addProduitFamille" id="addProduitFamille" class="form-control">
                            <?php
                            if (!empty($familles)):
                                foreach ($familles as $f):
                                    ?>
                                    <option value="<?php echo $f->getFamilleId(); ?>" <?php if( !empty($produit) && $produit->getProduitFamilleId() == $f->getFamilleId() ) echo 'selected'; ?>><?php echo $f->getFamilleNom(); ?></option>
                                    <?php
                                endforeach;
                            endif;
                            ?>
                        </select>
                    </div>
                </div>                
                <div class="form-group">
                    <label for="addProduitUnite" class="col-xs-3 control-label">Unité</label>
                    <div class="col-sm-4 col-xs-9">
                        <select name="addProduitUnite" id="addProduitUnite" class="form-control">
                            <option value="1" <?php if( !empty($produit) && $produit->getProduitUniteId() == 1 ) echo 'selected'; ?> >pièce</option>
                            <option value="2" <?php if( !empty($produit) && $produit->getProduitUniteId() == 2 ) echo 'selected'; ?> >m²</option>
                            <option value="3" <?php if( !empty($produit) && $produit->getProduitUniteId() == 3 ) echo 'selected'; ?> >mètre</option>
                        </select>
                    </div>
                </div>
                <div class="form-group has-success">
                    <label for="addProduitTVA" class="col-xs-3 control-label">Taux de TVA</label>
                    <div class="col-sm-2 col-xs-9">
                        <div class="input-group">
                            <input type="text" id="addProduitTVA" class="form-control input-sm requiredField" name="addProduitTVA" required value="<?php if( !empty($produit)) echo $produit->getProduitTVA(); ?>" >
                            <span class="input-group-addon">
                                %
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="addProduitStock" class="col-xs-3 control-label">Gestion des stocks</label>
                    <div class="col-sm-4 col-xs-9">
                        <input type="checkbox" name="addProduitStock" id="addProduitStock" class="" value="1" <?php if( !empty($produit) && $produit->getProduitGestionStock() ) echo 'checked'; ?> >                        
                    </div>
                </div>
                <div class="form-group">
                    <label for="addProduitBain" class="col-xs-3 control-label">Gestion des bains</label>
                    <div class="col-sm-4 col-xs-9">
                        <input type="checkbox" name="addProduitBain" id="addProduitBain" class="" value="1" <?php if( !empty($produit) && $produit->getProduitGestionBain() ) echo 'checked'; ?> >
                    </div>
                </div>
                <div class="form-group has-error">
                    <label for="addProduitMultiple" class="col-xs-3 control-label">Multiple <span class="asterix">*</span></label>
                    <div class="col-sm-4 col-xs-9">
                        <input type="text" id="addProduitMultiple" class="form-control input-sm requiredField" name="addProduitMultiple" value="<?php if( !empty($produit)) echo $produit->getProduitMultiple(); ?>" >
                    </div>
                </div>
                <div class="form-group has-error">
                    <label for="addProduitAchatUnitaire" class="col-xs-3 control-label">Prix achat unitaire <span class="asterix">*</span></label>
                    <div class="col-sm-4 col-xs-9">
                        <div class="input-group">
                            <input type="text" id="addProduitAchatUnitaire" class="form-control input-sm requiredField" name="addProduitAchatUnitaire" value="<?php if( !empty($produit)) echo $produit->getProduitPrixAchatUnitaire(); ?>" >
                            <span class="input-group-addon">€ HT</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="addProduitSeuilPalette" class="col-xs-3 control-label">Qté min palette</label>
                    <div class="col-sm-4 col-xs-9">
                        <div class="input-group">
                            <input type="text" id="addProduitSeuilPalette" class="form-control input-sm" name="addProduitSeuilPalette" value="<?php if( !empty($produit)) echo $produit->getProduitSeuilPalette(); ?>" >
                            <span class="input-group-addon">m²</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="addProduitAchatPalette" class="col-xs-3 control-label">Prix achat palette</label>
                    <div class="col-sm-4 col-xs-9">
                        <div class="input-group">
                            <input type="text" id="addProduitAchatPalette" class="form-control input-sm" name="addProduitAchatPalette" value="<?php if( !empty($produit)) echo $produit->getProduitPrixAchatPalette(); ?>" >
                            <span class="input-group-addon">€ HT</span>
                        </div>
                    </div>
                </div>
                <div class="form-group has-error">
                    <label for="addProduitVenteUnitaire" class="col-xs-3 control-label">Prix de vente <span class="asterix">*</span></label>
                    <div class="col-sm-4 col-xs-9">
                        <div class="input-group">
                            <input type="text" id="addProduitVenteUnitaire" class="form-control input-sm requiredField" name="addProduitVenteUnitaire" value="<?php if( !empty($produit)) echo $produit->getProduitPrixVente(); ?>" placeholder="HT" >
                            <span class="input-group-addon"><i class="glyphicon glyphicon-link"></i></span>
                            <input type="text" id="saisieProduitTTC" class="form-control input-sm requiredField" value="" placeholder="TTC" >
                            <span class="input-group-addon">€</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="addProduitPoids" class="col-xs-3 control-label">Poids par unité</span></label>
                    <div class="col-sm-4 col-xs-9">
                        <div class="input-group">
                            <input type="text" id="addProduitPoids" class="form-control input-sm requiredField" name="addProduitPoids" value="<?php if( !empty($produit)) echo $produit->getProduitPoids(); ?>" >
                            <span class="input-group-addon">Kg</span>
                        </div>
                    </div>
                </div>
            </div>    
            <div class="modal-footer">                
                <button class="btn btn-warning pull-right" type="submit" id="btnAddProduitSubmit" style="width: 100%;"></button>             
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>