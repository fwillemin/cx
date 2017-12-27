<div class="container-fluid">
    <div class="row">
        <div class="baseCX col-sm-10 col-sm-offset-1" style="border-bottom:1px solid black;">

            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group">                        
                        <div class="col-sm-12">
                            <input type="text" class="form-control" name="rechFacture" id="rechFacture" value="" placeholder="Facture N°" />
                        </div>
                    </div>
                </div>
                <div class="col-sm-8" style="text-align: center;">
                    <div class="btn-group">
                        <a href="<?php echo site_url('facturation/blNonFactures'); ?>" class="btn btn-default <?php if (!$this->uri->segment(2)) echo 'active'; ?>"><i class="glyphicon glyphicon-inbox"></i> BL non facturés</a> 
                        <a href="<?php echo site_url('facturation/facturesNonPayees'); ?>" class="btn btn-default <?php if ($this->uri->segment(2) == 'facturesNonPayees') echo 'active'; ?>"><i class="glyphicon glyphicon-eur"></i> Factures non payées</a> 
                        <a href="<?php echo site_url('facturation/encaissements'); ?>" class="btn btn-default <?php if ($this->uri->segment(2) == 'encaissements') echo 'active'; ?>"><i class="glyphicon glyphicon-credit-card"></i> Encaissements</a>
                        <a href="<?php echo site_url('facturation/caisse'); ?>" class="btn btn-default <?php if ($this->uri->segment(2) == 'caisse') echo 'active'; ?>"><i class="glyphicon glyphicon-piggy-bank"></i> Caisse</a>
                        <a href="<?php echo site_url('facturation/cheques'); ?>" class="btn btn-default <?php if ($this->uri->segment(2) == 'cheques') echo 'active'; ?>"><i class="glyphicon glyphicon-book"></i> Remise de chèques</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>