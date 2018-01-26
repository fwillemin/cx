<div class="container-fluid">
    <div class="row hidden-xs">

        <div class="baseCX col-sm-10 col-sm-offset-1">

            <div class="row" style="margin-top:5px;">
                <div class="col-sm-12">
                    <?php if ($this->session->userdata('venteId')): ?>
                        <h2 style="color:green;">
                            <div class="dropdown" style="position: relative; float:left; margin-right:10px;">
                                <button class="btn btn-default" id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-cog"></i>
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dLabel" style="text-align: center;">
                                    <?php if ($bdc->getBdcDelete()): ?>
                                        <li>
                                            <button class="btn btn-link" style="" data-bdcid="<?= $this->session->userdata('venteId'); ?>" id="btnReanimateBdc" >
                                                <i class="fa fa-hand-point-right"></i> Annuler la suppression
                                            </button>
                                        </li>
                                    <?php else: ?>
                                        <li>
                                            <button class="btn btn-link" style="color:orangered;" data-bdcid="<?= $this->session->userdata('venteId'); ?>" id="btnDelBdc" <?= $this->session->userdata('venteEtat') > 0 ? 'disabled' : ''; ?> >
                                                <i class="fa fa-trash"></i> Supprimer
                                            </button>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            Bon de commande N°<?= $this->session->userdata('venteId'); ?>
                            <?php
                            if ($bdc->getBdcDelete()):
                                echo '<span style="color:grey; font-size:18px;">Supprimé</span>';
                            endif;
                            ?>
                        </h2>
                        <?php
                    else:
                        echo '<span style="color:gold;"><h2>Bon de commande non enregistré pour le devis N°' . $this->session->userdata('venteDevisId') . '</h2></span>';
                    endif;
                    ?>
                </div>
            </div>
            <div class="row" style="margin-top:0px;">
                <div class="col-sm-8">

                    <div id="clientSelectionne">
                        <?php
                        if (!empty($client)):
                            if ($client->getClientType() == 1):
                                echo '<label class="label-info label">Particulier</label>';
                            else:
                                echo '<label class="label-primary label">Client professionel</label>';
                            endif;
                            ?>
                            <strong><?= '<br>' . $client->getClientNom() . ' ' . $client->getClientPrenom(); ?></strong><br>
                            <address>
                                <?php
                                echo $client->getClientAdresse1();
                                if ($client->getClientAdresse2() != ''):
                                    echo '<br>' . $client->getClientAdresse2();
                                endif;
                                echo '<br>' . $client->getClientCp() . ' ' . $client->getClientVille();
                                ?>
                            </address>
                        <?php endif;
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
                            Servi par : <?= $this->session->userdata('venteCollaborateur'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top:10px;">
                <div class="col-sm-12">
                    <table class="table table-condensed table-hover" id="ventesArticlesTable">
                        <thead>
                            <tr>
                                <th style="width:3%;"></th>
                                <th style="width:3%;">Ref</th>
                                <th style="width:38%;">Désignation</th>
                                <th style="width:7%;">TVA</th>
                                <th style="width:14%; text-align: center;">PU HT | TTC</th>
                                <th style="width:7%; text-align: center;">Quantité</th>
                                <th style="width:7%; text-align: center;">Unité</th>
                                <th style="width:7%; text-align: center;">Remise</th>
                                <th style="width:5%; text-align: right;">Total</th>
                                <th style="width:9%;">
                                    <i class="glyphicon glyphicon-info-sign"></i>
                                </th>
                                <th style="width:3%;">
                                    <i class="glyphicon glyphicon-erase"></i>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($this->cart->contents()):
                                foreach ($this->cart->contents() as $item):
                                    $bloque = FALSE;  /* On ne peut pas supprimer la ligne */
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
                                            <?php
                                            //if ( $this->session->userdata('venteId') && (!empty($item['options']['resteALivrer']) || $item['options']['resteALivrer'] == 0) ):
                                            if (isset($item['options']['resteALivrer'])):
                                                if ($item['options']['resteALivrer'] == 0):
                                                    echo '<label class="label label-success">Livré</label>';
                                                    $bloque = TRUE;
                                                elseif ($item['options']['resteALivrer'] < $item['qty'] && $item['options']['qteLivree'] > 0):
                                                    echo '<label class="label label-success">Partiellement livré</label>';
                                                    $bloque = TRUE;
                                                endif;
                                            else:
                                                echo '<span style="color:red;">Non enregistré !</span>';
                                            endif;
                                            ?>
                                        </td>
                                        <td>
                                            <?php if (!$bloque): ?>
                                                <i class="glyphicon glyphicon-erase btnArticleDelete tooltipOk" title="Double-click pour supprimer cet article" data-placement="left"></i>
                                            <?php endif;
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                endforeach;
                            endif;

                            if (!$bdc || $bdc->getBdcEtat() < 2): /* le Bdc n'est pas totalement livré */
                                echo form_open('ventes/addArticle', array('class' => 'form-inline', 'id' => 'formAddArticle'));
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
                                        <input type="text" id="addArticlePrixUnitaire" name="addArticlePrixUnitaire" value="" class="form-control input-sm" style="text-align: right;" >
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
                                <td colspan="2" style="text-align: center;">
                                    <div class="btn-group btn-group-sm">
                                        <button type="submit" class="btn btn-warning" id="btnAddArticleSubmit">Ajouter</button>
                                        <button class="btn btn-danger tooltipOk" title="Annuler la modification" data-placement="bottom" id="btnAddArticleReset" style="display:none;"><i class="glyphicon glyphicon-remove"></i></button>
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
                                <td colspan="3">
                                    <textarea <?php if (!$bdc || $bdc->getBdcEtat() >= 2) echo 'disabled'; ?> name="venteCommentaire" id="venteCommentaire" class="form-control" rows="6" style="background-color:#ededfc;" placeholder="Commentaire pour le bon de commande"><?= $this->session->userdata('venteCommentaire'); ?></textarea>
                                    <br>
                                    <div class="btn-group" style="position:relative;">
                                        <?php if (!$bdc || ($bdc->getBdcEtat() < 2 && !$bdc->getBdcDelete())): ?>
                                            <button class="btn btn-warning" id="btnBdcEnregistrer"><i class="glyphicon glyphicon-save"></i> <?php
                                                if ($this->session->userdata('venteId')):
                                                    echo 'Modifier';
                                                else:
                                                    echo 'Enregistrer';
                                                endif;
                                                echo ' le ' . $this->cxwork->affVenteType($this->session->userdata('venteType'));
                                                ?>
                                            </button>
                                            <button class="btn btn-danger tooltipOk" title="Double-click pour vider" data-placement="top" id="btnBdcReset">
                                                <i class="glyphicon glyphicon-erase"></i> Annuler
                                            </button>
                                            <button class="btn btn-primary" id="btnModalBl">
                                                <i class="glyphicon glyphicon-share"></i> Livrer
                                            </button>
                                            <?php
                                        endif;

                                        if ($this->session->userdata('venteId')) :
                                            ?>
                                            <a target="_blank" href="<?= site_url('documents/editionBdc/' . $this->session->userdata('venteId')); ?>" class="btn btn-default" ><i class="glyphicon glyphicon-print"></i></a>
                                            <?php if (file_exists('assets/Commande ' . $bdc->getBdcId() . '.pdf')): ?>
                                                <button class="btn btn-success" id="btnSendBdcEmail">
                                                    <i class="fas fa-envelope"></i>
                                                </button>
                                                <?php
                                            endif;
                                        endif;
                                        ?>
                                    </div>
                                </td>
                                <td colspan="4"></td>
                                <td colspan="4">
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
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>

            <?php
            /* si on peut livrer, on génére une liste de BL et une modal */
            if ($this->session->userdata('venteId')):
                ?>

                <div class="col-sm-3">
                    <h3>Bons de livraison</h3>
                    <table class="table table-bordered table-condensed" style="font-size: 13px; background-color: #FFF;">
                        <tr style="background-color: #04335a; color: #FFF;">
                            <td style="width: 20px; color: lightgray;"><i class="fas fa-envelope"></i></td>
                            <td>BL N°</td>
                            <td>Date</td>
                            <td style="text-align:center;">Facturer</td>
                        </tr>
                        <?php
                        $affBtnFacturer = FALSE;
                        if (!empty($bls)):
                            foreach ($bls as $bl):
                                if ($bl->getBlFactureId() == 0)
                                    $affBtnFacturer = TRUE;
                                ?>
                                <tr data-blid="<?= $bl->getBlId(); ?>">
                                    <td>
                                        <?php if (file_exists('assets/Bl ' . $bl->getBlId() . '.pdf')): ?>
                                            <button class="btn btn-link btn-xs btnSendBlEmail" style="padding: 0px;">
                                                <i class="fas fa-envelope"></i>
                                            </button>
                                        <?php endif;
                                        ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-link btn-xs btnAffBl tooltipOk" data-placement="right" title="Espace + click = non chiffré." data-blid="<?= $bl->getBlId(); ?>">
                                            <?= $bl->getBlId(); ?>
                                        </button>
                                    </td>
                                    <td><?= date('d/m/Y', $bl->getBlDate()); ?></td>
                                    <td style="text-align: center;">
                                        <?php
                                        if ($bl->getBlFactureId() > 0):
                                            if ($bl->getBlFacture()->getFactureTotalHT() > 0)
                                                $typeFacture = 'Facture';
                                            else
                                                $typeFacture = 'Avoir';
                                            echo '<a href="' . site_url('documents/editionFacture/' . $bl->getBlFactureId()) . '" target="_blank">' . $typeFacture . ' N° ' . $bl->getBlFactureId() . '</a>';
                                        else :
                                            ?>
                                            <input type="checkbox" class="blAFacturer" value="<?= $bl->getBlId(); ?>" >
                                            <i class="glyphicon glyphicon-erase tooltipOk pull-right delBl" cible="<?= $bl->getBlId(); ?>" bdc="<?= $bdc->getBdcId(); ?>" title="Doucle-click pour supprimer" data-placement="left"></i>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php
                            endforeach;
                            if ($affBtnFacturer):
                                ?>
                                <tr>
                                    <td colspan="3" style="text-align: right;">
                                        <button class="btn btn-warning tooltipOk" data-placement="left" title="Double-click pour facturer" id="btnAddFacture" bdc="<?= $this->session->userdata('venteId'); ?>">
                                            <i class="glyphicon glyphicon-list-alt"></i> Facturer
                                        </button>
                                    </td>
                                </tr>
                                <?php
                            endif;
                        endif;
                        ?>

                    </table>
                </div>
                <div class="col-sm-4">
                    <h3>Factures</h3>
                    <table class="table table-bordered table-condensed" style="font-size: 13px; background-color: #FFF;">
                        <tr style="background-color: #04335a; color: #FFF;">
                            <td style="width: 20px; color: lightgray;"></td>
                            <td>Facture N°</td>
                            <td>Date</td>
                            <td style="text-align:right;">Montant</td>
                            <td style="text-align:right;">Solde</td>
                            <td style="width: 20px;"></td>
                        </tr>
                        <?php
                        if ($factures):
                            foreach ($factures as $f):
                                echo '<tr data-factureid="' . $f->getFactureId() . '"><td>';
                                if (file_exists('assets/Facture ' . $f->getFactureId() . '.pdf')):
                                    echo '<button class="btn btn-link btn-xs btnSendFactureEmail" style="padding: 0px;"><i class="fas fa-envelope"></i></button>';
                                endif;
                                echo '</td><td><a href = "' . site_url('factures/ficheFacture/' . $f->getFactureId()) . '">' . $f->getFactureId() . '</a>'
                                . '</td><td>' . date('d/m/Y', $f->getFactureDate()) . '</td>'
                                . '<td style = "text-align: right;">' . number_format($f->getFactureTotalTTC(), 2, ',', ' ') . '</td>'
                                . '<td style = "text-align: right;">' . number_format($f->getFactureSolde(), 2, ',', ' ') . '</td>'
                                . '<td><a href = "' . site_url('documents/editionFacture/' . $f->getFactureId()) . '" target = "_blank"><i class = "fas fa-file-pdf"></i></a></td></tr>';
                            endforeach;
                        endif;
                        ?>
                    </table>
                </div>
                <div class="col-sm-5">
                    <h3>Réglements</h3>

                    <table class="table table-bordered table-condensed" style="font-size: 13px; background-color: #FFF;">
                        <tr style="background-color: #04335a; color: #FFF;">
                            <td></td>
                            <td>Date</td>
                            <td>Type</td>
                            <td>Objet</td>
                            <td>Montant</td>
                            <td>Mode</td>
                            <td style="width: 20px;"></td>
                        <tr>

                            <?php
                            if (!empty($reglements)):
                                foreach ($reglements as $r):
                                    ?>
                                <tr data-reglementid="<?= $r->getReglementId(); ?>" data-reglementMontant="<?= $r->getReglementMontant(); ?>" data-reglementmodeid="<?= $r->getReglementModeId(); ?>">
                                    <td>
                                        <?php
                                        if ($r->getReglementSecure()):
                                            if ($r->getReglementHistorique()):
                                                echo '<i class = "fas fa-copy" style = "color: purple;"></i>';
                                            else:
                                                echo '<i class = "fas fa-certificate" style = "color: green;"></i>';
                                            endif;
                                        else:
                                            echo '<i class = "fas fa-warning" style = "color: red;"></i>';
                                        endif;
                                        ?>
                                    </td>
                                    <td>
                                        <?= date('d/m/Y', $r->getReglementDate()); ?>
                                    </td>
                                    <td>
                                        <?= $r->getReglementType() == 1 ? 'Acompte' : 'Solde'; ?>
                                    </td>
                                    <td style="width:150px; padding:1px;">
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <select class="form-control input-sm reaffecteReglement" >
                                                    <option value="0">--</option>
                                                    <?php
                                                    if ($factures):
                                                        foreach ($factures as $f):
                                                            ?>
                                                            <option value="<?= $f->getFactureId(); ?>" <?php
                                                            if ($r->getReglementFactureId() == $f->getFactureId()): echo 'selected';
                                                            endif;
                                                            ?> >
                                                                Fact N°<?= $f->getFactureId(); ?></option>
                                                            <?php
                                                        endforeach;
                                                    endif;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                    </td>
                                    <td style="text-align: right;"><?= number_format($r->getReglementMontant(), 2, ', ', ' ') . '€';
                                                    ?></td>
                                    <td><?= $r->getReglementMode()->getModeReglementNom(); ?></td>
                                    <td style="text-align: center;">
                                        <?php
                                        if (date('d/m/Y', $r->getReglementDate()) == date('d/m/Y')):
                                            echo '<i class="fas fa-pencil-alt btnModReglement" style="cursor: pointer;"></i>';
                                        endif;
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            endforeach;
                        endif;
                        ?>

                    </table>

                    <?php
                    if ($bdc && !$bdc->getBdcDelete()):
                        echo form_open('factures/addReglement', array('class' => 'form-inline', 'id' => 'formAddReglement'));
                        ?>
                        <input type="hidden" name="addReglementId" id="addReglementId" value="">
                        <strong>Réglement : </strong>
                        <div class="input-group">
                            <input type="text" style="width:80px; text-align: right;" class="form-control input-sm" name="addReglementMontant" id="addReglementMontant" value="" required >
                            <span class="input-group-addon">€</span>
                        </div>
                        <select class="form-control input-sm" name="addReglementObjet" id="addReglementObjet" >
                            <option value="0">Acompte</option>
                            <?php
                            if (!empty($factures)):
                                foreach ($factures as $f):
                                    if ($f->getFactureSolde() > 0):
                                        echo '<option value="' . $f->getFactureId() . '">Fact N°' . $f->getFactureId() . '</option>';
                                    endif;
                                endforeach;
                            endif;
                            ?>
                        </select>
                        <select class="form-control input-sm" name="addReglementMode" id="addReglementMode" >
                            <option value="1">Carte bancaire</option>
                            <option value="2">Chèque</option>
                            <option value="3">Espèces</option>
                            <option value="4">Traite</option>
                            <option value="5">Virement</option>
                        </select>
                        <button class="btn btn-sm btn-primary" type="submit" id="btnAddReglementSubmit"><i class="glyphicon glyphicon-piggy-bank"></i> Payer</button>
                        <button class="btn btn-sm btn-default" type="button" id="btnAddReglementCancel" style="display: none;"><i class="fas fa-times"></i></button>
                        <br>
                        <input type="text" value="" placeholder="Motif" name="addReglementMotif" id="addReglementMotif" class="form-control" style="width: 100%; display: none;">
                        <div id="loaderReglement" class="la-ball-scale-pulse form-control" style="color:orangered; border: none; display:none;">
                            <div></div>
                            <div></div>
                        </div>
                        <?php echo form_close();
                    endif;
                    ?>
                </div>

                <?php
            endif;
            ?>

        </div>
    </div>
</div>

<!-- Livraison ----------------------------- -->
<div class="modal fade" id="modalLivraison" tabindex="-1" role="dialog" aria-labelledby="Livrer un BDC" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="glyphicon glyphicon-remove" style="color:#f50a1c;"> </i></button>
                <h4 class="modal-title"><strong>Livraison pour le bon de commande N°<?= $this->session->userdata('venteId'); ?></strong></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idVenteLivree" type="hidden" value="<?= $this->session->userdata('venteId'); ?>" > <!-- necessaire pour avoir l'id de la vente pour reload de la page en cas de livraison success -->
                <table class="table table-striped table-condensed" id="livraisonTable">
                    <thead>
                        <tr>
                            <th>Désignation</th>
                            <th style="text-align: center;">Commande</th>
                            <th>Déjà livrée</th>
                            <th style="text-align: center;">Livraison</th>
                            <th>stock a déduire</th>
                        </tr<
                    </thead>
                    <?php
                    if ($bdc && !empty($bdc->getBdcArticles())):
                        foreach ($bdc->getBdcArticles() as $a):
                            if (abs($a->getArticleQteLivree()) < abs($a->getArticleQte())) :
                                ?>
                                <tr class="articlesALivrer" id="<?= $a->getArticleId(); ?>">
                                    <td><a href="<?= site_url('produits/ficheProduit/' . $a->getArticleProduitId()); ?>" target="_blank"><?= $a->getArticleDesignation(); ?></a></td>
                                    <td style="text-align: center;"><?= $a->getArticleQte(); ?></td>
                                    <td style="text-align: center;"><?= $a->getArticleQteLivree(); ?></td>
                                    <td style="width:200px;">
                                        <?php
                                        if ($a->getArticleProduitId() > 0):
                                            $multiple = $a->getArticleProduit()->getProduitMultiple();
                                        else:
                                            $multiple = 1;
                                        endif;
                                        ?>
                                        <div class="input-group">
                                            <span class="input-group-addon">Livrer</span>
                                            <input type="text" class="form-control input-sm qteALivrer" value="<?= ($a->getArticleQte() - $a->getArticleQteLivree()) / $multiple; ?>" >
                                            <span class="input-group-addon"><?= 'x ' . $multiple . $this->cxwork->affUnite($a->getArticleUniteId()); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                            <?php if ($a->getArticleProduitId() && $a->getArticleProduit()->getProduitGestionStock() == 1): ?>
                                            <select class="form-control stockADeduire input-sm">
                                                <?php
                                                if (!empty($a->getArticleProduit()->getProduitStocks())):
                                                    foreach ($a->getArticleProduit()->getProduitStocks() as $s):
                                                        if ($a->getArticleProduit()->getProduitGestionBain() == 1):
                                                            echo '<option value="' . $s->getStockId() . '">' . $s->getStockQte() . ' ' . $this->cxwork->affUnite($a->getArticleUniteId()) . ' [' . $s->getStockBain() . ', ' . $s->getStockCalibre() . ']. PA' . $s->getStockPrixAchat() . '</option>';
                                                        else:
                                                            echo '<option value="' . $s->getStockId() . '">' . $s->getStockQte() . ' ' . $this->cxwork->affUnite($a->getArticleUniteId()) . ' PA' . $s->getStockPrixAchat() . '</option>';
                                                        endif;
                                                    endforeach;
                                                endif;
                                                ?>
                                            </select>
                                            <?php
                                        else: echo '<center><label class="label label-danger">Pas de gestion de stock</label></center>';
                                        endif;
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            endif;
                        endforeach;
                    endif;
                    ?>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn btn-gold btn-sm tooltipOk" title="Double click pour valider la livraison." data-placement="left" id="btnAddBl" cible=""> Livrer</button>
            </div>
        </div>
    </div>
</div>
