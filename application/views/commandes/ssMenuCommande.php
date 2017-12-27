<div class="container-fluid">
    <div class="row">
        <div class="baseCX col-sm-10 col-sm-offset-1" style="border-bottom:1px solid black;">

            <div class="row">
                <div class="col-sm-10" style="text-align: center;">
                    <div class="btn-group">
                        <a href="<?php echo site_url('commandes/'); ?>" class="btn btn-sm btn-default <?php if (!$this->uri->segment(2)) echo 'active'; ?>" id="btnProduits">Commande à passer</a>
                        <a href="<?php echo site_url('commandes/enCours'); ?>" class="btn btn-sm btn-default <?php if ($this->uri->segment(2) == 'enCours') echo 'active'; ?>" id="btnFamilles">Commandes en cours</a>            
                        <a href="<?php echo site_url('commandes/commandesRecues'); ?>" class="btn btn-sm btn-default <?php if ($this->uri->segment(2) == 'commandesRecues') echo 'active'; ?>" id="btnFamilles">Commandes reçues</a>            
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>