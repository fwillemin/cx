<div class="container-fluid">
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1 col-xs-12 baseCX">

            <div id="toolbar" class="btn-group">
                <button type="button" class="btn btn-warning" id="btnAddClient">
                    <i class="glyphicon glyphicon-plus"></i> Ajouter un client
                </button>
            </div>

            <table id="tableClients" style="font-size:12px;">

            </table>
            
            <ul id="context-menu" class="dropdown-menu" style="font-size:12px; border-top:1px solid grey;">
                <li data-item="fiche" style="cursor:pointer;"><a style="color: #ef8d1c;"><i class="glyphicon glyphicon-user"></i> Fiche Client</a></li>                
            </ul>
        </div>
    </div>
</div>
<?php include('formClient.php'); ?>

