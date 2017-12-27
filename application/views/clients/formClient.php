<!-- Ajout d'un client ------------------------------------------------------------------------------------------------------ -->
<div class="modal fade" id="modalAddClient" tabindex="-1" role="dialog" aria-labelledby="Ajouter un client" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="glyphicon glyphicon-remove" style="color:#f50a1c;"> </i></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('clients/addClient/', array('class' => 'form-horizontal', 'id' => 'formAddClient')); ?>
                <input type="hidden" name="addClientId" id="addClientId" value="" >

                <div class="form-group">
                    <label for="addClientType" class="col-xs-3 control-label">Type</label>
                    <div class="col-sm-9 col-xs-9">
                        <select name="addClientType" id="addClientType" class="form-control">
                            <option value="1">Particulier</option>
                            <option value="2">Professionnel</option>
                        </select>
                    </div>
                </div>                
                <div class="form-group">
                    <label for="addClientCodeComptable" class="col-sm-3 control-label">Code comptable</label>
                    <div class="col-sm-4">
                        <input type="text" id="addClientCodeComptable" class="form-control input-sm" name="addClientCodeComptable" value="" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="addClientIntracom" class="col-sm-3 control-label">TVA Intracom</label>
                    <div class="col-sm-4">
                        <input type="text" id="addClientIntracom" class="form-control input-sm" name="addClientIntracom" value="" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="addClientExo" class="col-sm-3 control-label">Exoneration de TVA</label>
                    <div class="col-sm-4">
                        <select class="form-control" name="addClientExo" id="addClientExo">
                            <option value="0">NON</option>
                            <option value="1">OUI</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="addClientRaisonSociale" class="col-sm-3 control-label">Raison sociale</label>
                    <div class="col-sm-9">
                        <input type="text" id="addClientRaisonSociale" class="form-control input-sm" name="addClientRaisonSociale" value="" >
                    </div>
                </div>
                <div class="form-group has-error">
                    <label for="addClientNom" class="col-sm-3 control-label">Nom <span class="asterix">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" id="addClientNom" class="form-control input-sm requiredField" name="addClientNom" required value="" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="addClientPrenom" class="col-sm-3 control-label">Prénom</label>
                    <div class="col-sm-9">
                        <input type="text" id="addClientPrenom" class="form-control input-sm" name="addClientPrenom" value="" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="addClientAdresse1" class="col-sm-3 control-label">Adresse</label>
                    <div class="col-sm-9">
                        <input type="text" id="addClientAdresse1" class="form-control input-sm" name="addClientAdresse1" value="" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="addClientAdresse2" class="col-sm-3 control-label">Complement d'adresse</label>
                    <div class="col-sm-9">
                        <input type="text" id="addClientAdresse2" class="form-control input-sm" name="addClientAdresse2" value="" >
                    </div>
                </div>
                <div class="form-group has-error">
                    <label class="col-sm-3 control-label" for="addClientCp">Code postal <span class="asterix">*</span></label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control input-sm requiredField" name="addClientCp" id="addClientCp" placeholder="Code postal" required value="" >
                    </div>
                    <label class="col-sm-1 control-label" for="addClientVille">Ville <span class="asterix">*</span></label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control input-sm requiredField" name="addClientVille" id="addClientVille" placeholder="Ville" required value="" >
                    </div>
                </div>
                <div class="form-group has-error">
                    <label for="addClientPays" class="col-sm-3 control-label">Pays</label>
                    <div class="col-sm-4">
                        <input type="text" id="addClientPays" class="form-control input-sm requiredField" name="addClientPays" value="FRANCE" required >
                    </div>
                </div>                
                <div class="form-group">
                    <label for="addClientTel" class="col-sm-3 control-label">Téléphone</label>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <input type="text" id="addClientTel"  class="form-control input-sm" name="addClientTel" value="" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="addClientPortable" class="col-sm-3 control-label">Portable</label>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <input type="text" id="addClientPortable"  class="form-control input-sm" name="addClientPortable" value="" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="addClientFax" class="col-sm-3 control-label">Fax</label>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <input type="text" id="addClientFax"  class="form-control input-sm" name="addClientFax" value="" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="addClientEmail" class="col-sm-3 control-label">Email</label>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <input type="email" id="addClientEmail" class="form-control input-sm" name="addClientEmail" value="" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="addClientModeReglement" class="col-sm-3 control-label">Mode de réglement</label>
                    <div class="col-sm-9">
                        <select name="addClientModeReglement" id="addClientModeReglement" class="form-control" required>
                            <?php
                            if (!empty($modes)):
                                foreach ($modes as $m):
                                    echo '<option value="' . $m->getModeReglementId() . '">' . $m->getModeReglementNom() . '</option>';
                                endforeach;
                            endif;
                            ?>                           
                        </select>
                    </div>
                </div>       
                <div class="form-group">
                    <label for="addClientConditionReglement" class="col-sm-3 control-label">Conditions de réglement</label>
                    <div class="col-sm-9">
                        <select name="addClientConditionReglement" id="addClientConditionReglement" class="form-control" required>   
                            <?php
                            if (!empty($conditions)):
                                foreach ($conditions as $c):
                                    echo '<option value="' . $c->getConditionReglementId() . '">' . $c->getConditionReglementNom() . '</option>';
                                endforeach;
                            endif;
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">                
                <button class="btn btn-warning pull-right" type="submit" id="btnAddClientSubmit"></button>             
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>