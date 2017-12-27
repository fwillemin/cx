<div class="row" style="margin-top:-10px;">
    <div class="col-lg-7 col-md-7 col-sm-7">
        <div class="btn-group">
            <?php
            if($this->session->userdata('venteType') == 1): ?>
                <button class="btn btn-default" id="btnDevisListe"><i class="glyphicon glyphicon-list-alt"></i> Liste des devis</button>
                <a class="btn btn-default" href="<?php echo site_url('ventes/resetDevisEncours'); ?>"><i class="glyphicon glyphicon-plus"></i> Créer un devis</a>
            <?php
            endif;
            if($this->session->userdata('venteType') == 2): ?>
                <a href="<?php echo site_url('ventes/bdcListe'); ?>" class="btn btn-default" id="btnDevisListe"><i class="glyphicon glyphicon-list-alt"></i> Liste des bons de commandes</button>
                <a class="btn btn-default" href="<?php echo site_url('ventes/bdcVentilation'); ?>"><i class="glyphicon glyphicon-plus"></i> Ventillation des commandes usine</a>
            <?php
            endif; ?>
        </div>
    </div>
    <div class="col-lg-5 col-md-5 col-sm-5" style="text-align: right;">
        <div class="btn-group btn-group-lg">
            <a href="<?php echo site_url('ventes/'); ?>" class="btn btn-default <?php if($this->session->userdata('venteType') == 1) echo 'active'; ?>" id="btnModeDevis">Devis</a>
            <a href="<?php echo site_url('ventes/bdcListe'); ?>" class="btn btn-default <?php if($this->session->userdata('venteType') == 2) echo 'active'; ?>" id="btnModeBC">Bon de commande</a>            
        </div>
    </div>
</div>