<div class="container">
    <?php include('ssMenuProduit.php'); ?>
    <div class="row">
        <div class="col-xs-12" style="background-color: #FFF; padding: 10px 10px 10px 10px; border:1px solid lightgray;">

            <div id="toolbar" class="btn-group">
                <div class="btn-group pull-right">
                    <button class="btn btn-sm btn-warning" id="btnAddProduit"><i class="glyphicon glyphicon-plus"></i> Ajouter un Produit</button>
                    <button class="btn btn-sm btn-info" id="btnPrint"><i class="glyphicon glyphicon-print"></i> Listing</button>
                </div>
            </div>

            <table id="tableProduits" style="font-size:12px;">

            </table>

            <ul id="context-menu" class="dropdown-menu" style="font-size:12px; border-top:1px solid grey;">
                <li data-item="fiche" style="cursor:pointer;"><a style="color: #ef8d1c;"><i class="glyphicon glyphicon-user"></i> Fiche Produit</a></li>
            </ul>
        </div>
    </div>
    <?php include('formProduit.php'); ?>
</div>

