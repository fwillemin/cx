<div class="row baseCX" style="padding:5px;">

    <div class="col-xs-12" style="text-align: center;">
        <div class="btn-group">
            <a href="<?php echo site_url('produits/'); ?>" class="btn btn-sm btn-default <?php if (!$this->uri->segment(2)) echo 'active'; ?>" id="btnProduits">Produits</a>
            <a href="<?php echo site_url('produits/familles'); ?>" class="btn btn-sm btn-default <?php if ($this->uri->segment(2) == 'familles') echo 'active'; ?>" id="btnFamilles">Familles</a>
            <a href="<?php echo site_url('produits/usines'); ?>" class="btn btn-sm btn-default <?php if ($this->uri->segment(2) == 'usines') echo 'active'; ?>" id="btnUsines">Usines</a>
        </div>
    </div>
</div>
