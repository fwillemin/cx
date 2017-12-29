<div class="container-fluid">
    <div class="row" id="ventesNew">

        <div class="baseCX col-sm-10 col-sm-offset-1">

            <?php
            if ($this->session->userdata('venteId') && $this->session->userdata('venteType') == 1):
                $content = "<div style='text-align: center;'>"
                        . "<a class='btn btn-link btn-sm' href='" . site_url('chiffrages/dupliquerDevis/' . $this->session->userdata('venteId')) . "'><i class='fa fa-copy'></i> Dupliquer</a>";
                if ($this->session->userdata('venteEtat') == 0):
                    $content .= "<br><a class='btn btn-link btn-sm' href='" . site_url('chiffrages/devisPerdu/' . $this->session->userdata('venteId')) . "'><i class='fa fa-thumbs-down'></i> Devis perdu</a>";
                elseif ($this->session->userdata('venteEtat') == 1):
                    $content .= "<br><a class='btn btn-link btn-sm' href='" . site_url('chiffrages/devisEncours/' . $this->session->userdata('venteId')) . "'><i class='fa fa-thumbs-up'></i> Reprendre</a>";
                endif;
                if ($this->session->userdata('venteEtat') < 2):
                    $content .= "<hr><a class='btn btn-danger btn-sm' href='" . site_url('chiffrages/deleteDevis/' . $this->session->userdata('venteId')) . "'><i class='fa fa-trash'></i> Supprimer</a>";
                endif;
                $content .= "</div>";
            else:
                $content = "<div style='text-align: center;'>Le devis en cours n'est pas enregistré</div>";
            endif;
            ?>

            <div class="row">
                <div class="col-sm-12">
                    <?php if ($this->session->userdata('venteId')): ?>
                        <h2 style="color:green; margin-bottom: 0px">
                            <button type="button" class="btn btn-sm btn-default" data-toggle="popover" title="Options" data-placement="bottom" data-html="true"
                                    data-content="<?= $content; ?>">
                                <i class="fas fa-cog"></i>
                            </button>
                            <?php
                            echo 'Devis N°' . $this->session->userdata('venteId');
                            switch ($this->session->userdata('venteEtat')):
                                case 1:
                                    echo ' <span style="font-size: 18px; color: grey;">Perdu</span>';
                                    break;
                                case 2:
                                    echo ' <span style="font-size: 18px; color: grey;">Supprimé</span>';
                                    break;
                            endswitch;
                        else:
                            echo '<h2 style="color: goldenrod;">Devis non enregistré';
                        endif;
                        ?>
                    </h2>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8">

                    <input type="hidden" class="form-control" name="venteClientId" id="venteClientId" value="" >
                    <div id="clientSelectionne" style="margin-top:15px;">
                        <?php
                        if (!empty($client)):
                            echo '<button class="btn btn-link btn-sm" id="btnClientSearch"><i class="fas fa-pencil-alt"></i> Modifier le client </button><br>';
                            if ($client->getClientType() == 2):
                                echo '<label class="label-primary label">PRO</label> ';
                                echo ' <span style="font-weight: bold;">' . $client->getClientRaisonSociale() . '</span><br>';
                            endif;
                            ?>
                            <strong><?= $client->getClientNom() ? $client->getClientNom() . ' ' . $client->getClientPrenom() : ''; ?></strong>
                            <br>
                            <address>
                                <?php
                                echo $client->getClientAdresse1();
                                if ($client->getClientAdresse2() != ''):
                                    echo '<br>' . $client->getClientAdresse2();
                                endif;
                                echo '<br>' . $client->getClientCp() . ' ' . $client->getClientVille();
                                ?>
                            </address>
                            <?php
                        else:
                            echo '<button class="btn btn-warning btn-sm" id="btnClientSearch"><i class="fas fa-user-circle"></i> Selectionner le client </button><br>';
                        endif;
                        ?>
                    </div>

                </div>
                <div class="col-sm-4" style="text-align: right;">
                    <div class="form-group">
                        <label for="venteDate" class="col-sm-6 control-label">Date</label>
                        <div class="col-sm-6">
                            <input <?php if ($this->session->userdata('venteEditable') == FALSE) echo 'disabled'; ?> type="date" class="form-control input-sm venteMaj" name="venteDate" id="venteDate" value="<?php
                            if ($this->session->userdata('venteDate'))
                                echo date('Y-m-d', $this->session->userdata('venteDate'));
                            else
                                echo date('Y-m-d');
                            ?>" >
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="venteCollaborateurId" class="col-sm-6">Servi par :</label>
                        <div class="col-sm-6">
                            <select name="venteCollaborateurId" id="venteCollaborateurId" class="form-control venteMaj" style="width:200px;">
                                <option value="0">Choisir</option>
                                <?php
                                if ($collaborateurs):
                                    foreach ($collaborateurs as $u):
                                        ?>
                                        <option value="<?= $u->getCollaborateurId(); ?>" <?php if ($this->session->userdata('venteCollaborateurId') == $u->getCollaborateurId()) echo 'selected'; ?>><?= $u->getCollaborateurNom(); ?></option>
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top:10px;">
                <div class="col-sm-12">
                    <table class="table table-condensed table-hover" id="ventesArticlesTable">
                        <thead>
                        <th style="width:3%;"></th>
                        <th style="width:3%;">Ref</th>
                        <th style="width:38%;">Désignation</th>
                        <th style="width:7%;">TVA</th>
                        <th style="width:16%; text-align: center;">PU HT | TTC</th>
                        <th style="width:7%; text-align: center;">Quantité</th>
                        <th style="width:7%; text-align: center;">Unité</th>
                        <th style="width:10%; text-align: center;">Remise</th>
                        <th style="width:15%; text-align: right;">Total</th>
                        <th><i class="glyphicon glyphicon-erase"></i> </th>
                        </thead>
                        <tbody>
                            <?php
                            if ($this->cart->contents()):
                                foreach ($this->cart->contents() as $item):
                                    ?>
                                    <tr class="ligneVente" id="<?= $item['rowid']; ?>">
                                        <td><?= $item['options']['tauxMarge']; ?></td>
                                        <td><?= $item['options']['produitId']; ?></td>
                                        <td><?= $item['name']; ?></td>
                                        <td><?= $item['options']['tauxTVA']; ?></td>
                                        <td style="text-align: center;">
                                            <?= number_format($item['options']['prixUnitaire'], 2, ',', ' '); ?>
                                            <i class="glyphicon glyphicon-resize-horizontal"></i>
                                            <?= number_format($item['options']['prixUnitaire'] * (1 + $item['options']['tauxTVA'] / 100), 2, ',', ' '); ?>
                                        </td>
                                        <td style="text-align: center;"><?= number_format($item['qty'], 2, ',', ' '); ?></td>
                                        <td style="text-align: center;"><?= $this->cxwork->affUnite($item['options']['uniteId']); ?></td>
                                        <td style="text-align: center;"><?= number_format($item['remise'], 0, ',', ' '); ?></td>
                                        <td style="text-align: right;"><?= number_format($item['price'] * $item['qty'], 2, ',', ' '); ?></td>
                                        <td>
                                            <?php if ($this->session->userdata('venteEditable')): ?>
                                                <i class="glyphicon glyphicon-erase btnArticleDelete tooltipOk" title="Double-click pour supprimer cet article" data-placement="left"></i>
                                            <?php endif;
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                endforeach;
                            endif;

                            if ($this->session->userdata('venteEditable') == TRUE):
                                echo form_open('chiffrages/addArticle', array('class' => 'form-inline', 'id' => 'formAddArticle'));
                                ?>
                            <input type="hidden" id="addArticleRowid" name="addArticleRowid" value="" >
                            <input type="hidden" id="addArticleId" name="addArticleId" value="" >
                            <input type="hidden" id="addArticleProduitId" name="addArticleProduitId" value="" >
                            <input type="hidden" id="addArticleMultiple" name="venteArticleMultiple" value="" >
                            <tr>
                                <td colspan="2">
                                </td>
                                <td>
                                    <input type="text" id="addArticleDesignation" name="addArticleDesignation" value="" class="form-control input-sm" autocomplete="false"
                                           data-toggle="popover" data-trigger="click" data-placement="bottom" data-html="true" data-width="550"
                                           data-template ='<div class="popover" role="tooltip" style="max-width: 100%;"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
                                           data-content='<div><table border="1" class="table table-condensed" id="produitSearchTable">
                                           <thead>
                                           <tr>
                                           <th>Désignation</th>
                                           <th>Usine</th>
                                           <th>Prix unitaire</th>
                                           <th>Conditionnement</th>
                                           <th>Stocks</th>
                                           </tr>
                                           </thead>
                                           <tbody>

                                           </tbody>
                                           </table></div>'>
                                </td>
                                <td>
                                    <select name="addArticleTauxTVA" id="addArticleTauxTVA" class="form-control input-sm">
                                        <option value="20">20%</option>
                                        <option value="10">10%</option>
                                    </select>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="text" id="addArticlePrixUnitaire" name="addArticlePrixUnitaire" value="" class="form-control input-sm" >
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-link"></i></span>
                                        <input type="text" id="saisieTTC" value="" class="form-control input-sm" >
                                    </div>
                                </td>
                                <td><input type="text" id="addArticleQte" name="addArticleQte" value="" class="form-control input-sm" required ></td>
                                <td>
                                    <select class="form-control" name="addArticleUniteId" id="addArticleUniteId" size="1">
                                        <option value="1">U</option>
                                        <option value="2">m²</option>
                                        <option value="3">ml</option>
                                    </select>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="text" id="addArticleRemise" name="addArticleRemise" value="0" class="form-control input-sm" >
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </td>
                                <td><input type="text" id="addArticleTotal" name="addArticleTotal" value="" class="form-control-blank-right input-sm" disabled="disabled" ></td>
                            </tr>
                            <tr>
                                <td colspan="8">
                                    <div class="btn-group btn-group-sm pull-right">
                                        <button type="submit" class="btn btn-warning" id="btnAddArticleSubmit">Ajouter</button>
                                        <button class="btn btn-danger tooltipOk" title="Annuler la modification" data-placement="bottom" id="btnAddArticleReset" style="display:none;"><i class="fas fa-close"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <?php
                            echo form_close();
                        endif;
                        ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6">
                                    <div class="btn-group btn-group" style="position:absolute; bottom:0px;">
                                        <?php
                                        if ($this->session->userdata('venteEtat') == 0):
                                            if ($this->session->userdata('venteEditable') == TRUE):
                                                ?>
                                                <button class="btn btn-warning" id="btnDevisEnregistrer"><i class="glyphicon glyphicon-save"></i> <?php
                                                    if ($this->session->userdata('venteId'))
                                                        echo 'Modifier';
                                                    else
                                                        echo 'Enregistrer';
                                                    echo ' le devis';
                                                    ?>
                                                </button>
                                                <?php if ($this->session->userdata('venteId') && $this->session->userdata('venteId') > 0): ?>
                                                    <a class="btn btn-primary" href="<?= site_url('chiffrages/generationBdc'); ?>"><i class="glyphicon glyphicon-shopping-cart"></i> Générer le Bdc</a>
                                                    <?php
                                                endif;
                                            else:
                                                ?>
                                                <a class="btn btn-primary" href="<?= site_url('chiffrages/reloadBdc/' . $this->session->userdata('venteBdcId')); ?>"><i class="glyphicon glyphicon-shopping-cart"></i> Voir le Bdc</a>
                                            <?php
                                            endif;
                                        endif;
                                        if ($this->session->userdata('venteId')) :
                                            ?>
                                            <a target="_blank" href="<?= site_url('documents/editionDevis/' . $this->session->userdata('venteId')); ?>" class="btn btn-default" ><i class="fas fa-print"></i> Imprimer</a>
                                            <button class="btn btn-success" id="btnSendEmail">
                                                <i class="fas fa-envelope"></i>
                                            </button>
                                        <?php endif;
                                        ?>
                                    </div>
                                </td>
                                <td colspan="2">
                                    <table class="table table-condensed">
                                        <tbody>
                                            <tr>
                                                <td>Total HT</td>
                                                <td style="text-align: right;"><?= number_format($this->cart->total(), 2, ',', ' ') . ' €'; ?></td>
                                            </tr>
                                            <?php
                                            if (!empty($this->session->userdata('venteTVA'))):
                                                foreach ($this->session->userdata('venteTVA') as $taux => $value):
                                                    ?>
                                                    <tr>
                                                        <td><?= 'TVA ' . $taux . '%'; ?></td>
                                                        <td style="text-align: right;">
                                                            <?php
                                                            if ($taux == 0):
                                                                echo $value;
                                                            else:
                                                                echo number_format($value, 2, ',', ' ') . ' €';
                                                            endif;
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>
                                            <tr>
                                                <td>Total TTC</td>
                                                <td style="text-align: right; font-weight: bold;"><?= number_format($this->session->userdata('venteTTC'), 2, ',', ' ') . ' €'; ?></td>
                                            </tr>
                                            <tr>
                                                <td><i class="glyphicon glyphicon-scale"></i> Poids</td>
                                                <td style="text-align: right;"><?= number_format($this->session->userdata('ventePoids'), 2, ',', ' ') . ' Kg'; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tfoot>

                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- client_search -->
<div class="modal fade" id="modalClientSearch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="btn-group pull-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="btnAddClient">
                        <i class="fas fa-plus"></i> Ajouter un client
                    </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">
                        <i class="glyphicon glyphicon-remove"> </i>
                    </button>
                </div>
                <h4 class="modal-title"><i class="glyphicon glyphicon-search"></i> Selectionnez un client</h4>

            </div>
            <div class="modal-body" id="modal_body_ft">

                <input type="text" class="form-control" id="clientSearch" placeholder="Rechercher un client" >

                <table class="table table-condensed table-striped table-bordered table-hover" id="clientSearchTable" style="margin-top:10px;">
                    <thead>
                    <th>Raison sociale</th>
                    <th>Nom</th>
                    <th>Ville</th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?php include('application/views/clients/formClient.php'); ?>
