<div class="container">
    <div class="row">
        <div class="col-xs-12" style="background-color: #FFF; padding: 10px 10px 10px 10px; border:1px solid lightgray;">

            <a class="btn btn-primary" href="<?php echo site_url('ventes/resetDevisEncours'); ?>" style="width: 100%;">
                <i class="glyphicon glyphicon-plus"></i> Nouveau devis
            </a>

            <table id="tableDevis" style="font-size:12px;">

            </table>
            <!--
                        <ul id="context-menu" class="dropdown-menu" style="font-size:12px; border-top:1px solid grey; background-color: lightgray;">
                            <li data-item="fiche" style="cursor:pointer;">
                                <a style="color: #000"><i class="glyphicon glyphicon-file"></i> Consulter</a>
                            </li>
                            <li class="divider"></li>
                            <li class="divider"></li>
                            <li data-item="effacer" style="cursor:pointer;">
                                <a style="color: orangered;"><i class="glyphicon glyphicon-remove"></i> Supprimer</a>
                            </li>
                        </ul>-->
        </div>
        <button class="btn btn-sm btn-link" id="purgeDevis">
            Purger les devis
        </button>
        <div class="alert alert-danger ombre" style="display:none; font-size:14px; position:relative; top:-250px; z-index: 10; text-align: center; width: 400px;" id="confirmPurgeDevis">
            <i class="glyphicon glyphicon-alert" style="font-size:25px;"></i><br>
            Vous êtes sur le point de supprimer les devis <span style="text-decoration: underline;">non convertis</span> réalisés avant le
            <input type="date" id="limitePurge" value="<?= date('Y') - 1 . '-' . date('m-d'); ?>" class="form-control">
            <br><strong style="color: red;">Cette manipulation est irréversible.</strong>
            <hr>
            <button class="btn btn-default pull-left" id="btnPurgeDevisAvort"><i class="glyphicon glyphicon glyphicon-ok-sign" style="color:green;"></i> Stopper la purge</button>
            <button class="btn btn-danger pull-right" id="btnPurgeDevisConfirm"><i class="glyphicon glyphicon-erase"></i> Purger</button>
            <span id="loader" style="display: none;">
                <i class="fas fa-spin fa-superpowers"></i> Traitement en cours
            </span>
            <br>
        </div>
    </div>
</div>
