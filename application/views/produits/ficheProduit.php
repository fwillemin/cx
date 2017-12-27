<?php
if ($produit->getProduitDispo() > 0):
    $color = 'green';
else:
    $color = 'red';
endif;
?>
<div class="row ">
    <div class="col-sm-10 col-sm-offset-1 col-xs-12 baseCX" id="produitsFiche">
        <div class="row" style="margin-top:0px;">
            <div class="col-sm-10 col-xs-12">

                <h2><i class="glyphicon glyphicon-stop" style="color:<?php echo $color; ?>"></i> <?php echo $produit->getProduitDesignation(); ?></h2>

                <table class="table table-bordered">
                    <thead style="font-weight : bold;">
                    <th>Usine</th>
                    <th>Ref usine</th>
                    <th>Famille</th>
                    <th style="text-align: center;">Multiple</th>
                    <th style="text-align: center;" title="Prix Achat au m² HT">PAU</div></th>
                    <th style="text-align: center;" title="Quantité de commande minimum pour bénéficier du prix palette">Seuil palette</th>
                    <th style="text-align: center;" title="Prix Achat palette au m² HT">PAP</th>
                    <th style="text-align: center;" title="Prix de vente au m² HT">PV</th>
                    <th style="text-align: center;" title="Poids en Kg par unité">Poids</th>
                    <th style="text-align: center;" title="Gestion des stocks avec Bain et Calibre">B&C</th>
                    <th style="border-right: 2px solid #000; width:50px;"></th>
                    <th style="text-align: center; border: 2px solid #000; background-color: lightgray;" title="Stock réél">Stock physique</th>
                    <th style="text-align: center; border: 2px solid #000; background-color: lightgray;" title="Stock disponible">Stock disponible</th>
                    <th style="text-align: center; border: 2px solid #000; background-color: lightgray;" title="En commande">En commande</th>

                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $produit->getProduitUsine()->getUsineNom(); ?></td>
                            <td><?php echo $produit->getProduitRefUsine(); ?></td>
                            <td><?php echo $produit->getProduitFamille()->getFamilleNom(); ?></td>
                            <td style="text-align: center;"><?php echo '<span id="produitMultiple">' . $produit->getProduitMultiple() . '</span>' . $this->cxwork->affUnite($produit->getProduitUniteId()); ?></td>
                            <td style="text-align: center;"><?php echo number_format($produit->getProduitPrixAchatUnitaire(), 2, ',', ' ') . '€'; ?></td>
                            <td style="text-align: center;"><?php echo $produit->getProduitSeuilPalette(); ?></td>
                            <td style="text-align: center;"><?php echo number_format($produit->getProduitPrixAchatPalette(), 2, ',', ' ') . '€'; ?></td>
                            <td style="text-align: center;"><?php echo number_format($produit->getProduitPrixVente(), 2, ',', ' ') . '€'; ?></td>
                            <td style="text-align: center;"><?php echo number_format($produit->getProduitPoids(), 2, ',', ' '); ?></td>
                            <td style="text-align: center;">
                                <?php
                                if ($produit->getProduitGestionBain() == 1)
                                    echo '<i class="glyphicon glyphicon-check" style="color: green;"></i>';
                                else
                                    echo '-';
                                ?>
                            </td>
                            <td style="border-right: 2px solid #000;"></td>
                            <td style="text-align: center; border: 2px solid #000; background-color: lightslategray; color: #FFF;"><?= $produit->getProduitStock(); ?></td>
                            <td style="text-align: center; border: 2px solid #000; background-color: lightslategray; color: lime; font-weight: bold;"><?= $produit->getProduitDispo(); ?></td>
                            <td style="text-align: center; border: 2px solid #000; background-color: lightslategray; color: #FFF;"><?= $produit->getProduitEnCommande(); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-sm-2 hidden-xs" style="text-align: right; position:relative;">
                <div class="btn-group-vertical" data-produitid="<?php echo $produit->getProduitId(); ?>">
                    <button id="btnModProduit" class="btn btn-warning"><i class="fas fa-pencil-alt"></i> Modifier</button>
                    <button id="btnCopyProduit" class="btn btn-default"><i class="glyphicon glyphicon-copy"></i> Dupliquer</button>
                    <button id="btnDelProduit" class="btn btn-danger"><i class="glyphicon glyphicon-erase"></i> Supprimer</button>
                </div>
                <div class="alert alert-danger ombre" style="display:none; font-size:14px; position:absolute; top:100px; z-index: 10; text-align: center;" id="confirmDelArticle">
                    <i class="glyphicon glyphicon-alert" style="font-size:25px;"></i><br>
                    La suppression de cet article implique <strong>la suppression de ses stocks.</strong>.<br>
                    Si une vente ou une commande fournisseur concerne cet article, l'article sera archivé et non supprimé<br><br>
                    <button class="btn btn-default" id="btnDelProduitAvort"><i class="glyphicon glyphicon glyphicon-ok-sign" style="color:green;"></i> Conserver l'article</button>
                    <br>ou<br>
                    <button class="btn btn-danger btn-sm" id="btnDelProduitConfirm" data-produitid="<?php echo $produit->getProduitId(); ?>"><i class="glyphicon glyphicon-erase"></i> Supprimer</button>
                </div>
            </div>
        </div>
        <hr>
        <div class="row" style="margin-top:5px;">

            <div class="col-sm-6">
                <h3>Stocks</h3>

                <?php echo form_open('produits/addStock', array('class' => 'form-inline', 'id' => 'formAddStock')); ?>
                <div class="input-group">
                    <span class="input-group-addon" style="font-weight: bold;">Ajouter</span>
                    <input type="hidden" name="addStockProduitId" id="addStockProduitId" value="<?php echo $produit->getProduitId(); ?>" >
                    <input type="text" value="" name="addStockQte" id="addStockQte" class="form-control" style="width:90px;" >
                    <span class="input-group-addon"><?php echo $this->cxwork->affUnite($produit->getProduitUniteId()); ?></span>
                </div>
                <input type="text" value="" name="addStockBain" id="addStockBain" class="form-control" placeholder="Bain" style="width:80px;" <?php if ($produit->getProduitGestionBain() == 0) echo 'disabled'; ?> >
                <input type="text" value="" name="addStockCalibre" id="addStockCalibre" class="form-control" placeholder="Calibre" style="width:80px;" <?php if ($produit->getProduitGestionBain() == 0) echo 'disabled'; ?> >
                <div class="input-group">
                    <input type="text" value="<?php echo $produit->getProduitPrixAchatUnitaire(); ?>" name="addStockPrixAchat" id="addStockPrixAchat" class="form-control" placeholder="PA HT" style="width:90px;" >
                    <span class="input-group-addon">€</span>
                </div>
                <button class="btn btn-info" type="submit"><i class="glyphicon glyphicon-plus"></i> Ajouter</button>
                <?php echo form_close(); ?>

                <br>
                <table class="table table-condensed table-striped" style="width: 80%;">
                    <thead>
                    <th>Quantité</th>
                    <th>Bain</th>
                    <th>Calibre</th>
                    <th>Prix Achat</th>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($produit->getProduitStocks())):
                            foreach ($produit->getProduitStocks() as $s):
                                ?>
                                <tr class="ligneStock">
                                    <td><?php echo number_format($s->getStockQte(), 2, ',', ' ') . ' ' . $this->cxwork->affUnite($produit->getProduitUnite()); ?></td>
                                    <td><?php echo $s->getStockBain(); ?></td>
                                    <td><?php echo $s->getStockCalibre(); ?></td>
                                    <td><?php echo number_format($s->getStockPrixAchat(), 2, '.', ' ') . '€'; ?></td>
                                </tr>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="col-sm-6">
                <h3>Commandes usine</h3>
                <?php echo form_open('commandes/addAppro', array('class' => 'form-inline', 'id' => 'formAddAppro')); ?>

                <div class="input-group">
                    <span class="input-group-addon" style="font-weight: bold;">Commander</span>
                    <input type="hidden" name="addApproProduitId" id="addApproProduitId" value="<?php echo $produit->getProduitId(); ?>" >
                    <input type="text" value="" name="addApproQte" id="addApproQte" class="form-control" >
                    <span class="input-group-addon"><?php echo $this->cxwork->affUnite($produit->getProduitUniteId()); ?></span>
                </div>
                <button class="btn btn-info" type="submit"><i class="glyphicon glyphicon-plus"></i> Commander</button>
                <?php echo form_close(); ?>

                <br>
                <table class="table table-condensed">
                    <thead>
                    <th>Commande N°</th>
                    <th>Date</th>
                    <th>Qté commande</th>
                    <th>Qté reçue</th>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($appros)):
                            foreach ($appros as $a):
                                ?>
                                <tr>
                                    <td><a href="<?php echo site_url('commandes/ficheCommande/' . $a->getApproCommandeId()); ?>"><?php echo $a->getApproCommandeId(); ?></a></td>
                                    <td><?php echo date('d/m/Y', $a->getApproCommandeDate()); ?></td>
                                    <td><?php echo number_format($a->getApproQte(), 2, ',', ' '); ?></td>
                                    <td><?php echo number_format($a->getApproQteRecu(), 2, ',', ' '); ?></td>
                                </tr>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <h3>Historique des ventes</h3>
                <?php
                $i = 0; /* nombre de vente */
                $j = 0; /* Qté vendue */
                $CA = 0; /* Chiffre d'affaire de l'article */
                if (!empty($ventes)):
                    foreach ($ventes as $v):

                        if ($v->getArticlePrixUnitaire() >= 0):
                            $i++;
                            $j += $v->getArticleQte();
                            $CA += $v->getArticleTotalHT();
                        else:
                            $j -= $v->getArticleQte();
                            $CA += $v->getArticleTotalHT();
                        endif;

                    endforeach;
                endif;
                ?>
                <table class="table table-bordered" align="center"  style="text-align: center;">
                    <tr>
                        <td style="background-color:#f1f5fb; width:200px;">Nombre de ventes</td>
                        <td><?php echo $i; ?></td>
                    </tr>
                    <tr>
                        <td style="background-color:#f1f5fb;">Qté vendue</td>
                        <td><?php echo $j; ?></td>
                    </tr>
                    <tr>
                        <td style="background-color:#f1f5fb;">Qté moyenne par vente</td>
                        <td>
                            <?php
                            if ($i > 0):
                                echo number_format(round($j / $i, 2), 2, ',', ' ') . ' ' . $this->cxwork->affUnite($ventes[0]->getArticleUniteId());
                            else:
                                echo '-';
                            endif;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#f1f5fb;">Prix de vente moyen</td>
                        <td>
                            <?php
                            if ($i > 0):
                                echo number_format(round($CA / $j, 2), 2, ',', ' ') . '€';
                            else:
                                echo '-';
                            endif;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#f1f5fb;">Chiffre d'affaire</td>
                        <td><?php echo number_format($CA, 2, ',', ' ') . '€'; ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-8" style="padding-top: 30px;">
                <table class="table table-condensed table-striped" style="font-size: 12px;">
                    <thead>
                    <th style="width: 100px;">Date</th>
                    <th style="width: 100px;">Bdc</th>
                    <th>Client</th>
                    <th style="text-align: center;">Quantité (<?php if (!empty($ventes)) echo $this->cxwork->affUnite($ventes[0]->getArticleUniteId()); ?>)</th>
                    <th style="text-align: center;">PU HT</th>
                    <th style="text-align: center;">PU TTC</th>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($ventes)):
                            foreach ($ventes as $v):
                                ?>
                                <tr class="clientsLigne">
                                    <td><?php echo date('d/m/Y', $v->getArticleBdc()->getBdcDate()); ?></td>
                                    <td><a href="<?php echo site_url('ventes/reloadBdc/' . $v->getArticleBdcId()); ?>" target="_blank"><?php echo $v->getArticleBdcId(); ?></a></td>
                                    <td>
                                        <?php
                                        if ($v->getArticleBdc()->getBdcClient()->getClientType() == 1):
                                            echo $v->getArticleBdc()->getBdcClient()->getClientNom() . ' ' . $v->getArticleBdc()->getBdcClient()->getClientPrenom();
                                        else:
                                            echo $v->getArticleBdc()->getBdcClient()->getClientRaisonSociale();
                                        endif;
                                        ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php echo number_format($v->getArticleQte(), 2, ',', ' '); ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php
                                        $prixVente = round($v->getArticlePrixUnitaire() * (100 - $v->getArticleRemise()) / 100, 2);
                                        echo number_format($prixVente, 2, ',', ' ');
                                        if ($v->getArticleRemise() > 0):
                                            echo '<span style="font-size:10px; color:grey;">(-' . $v->getArticleRemise() . '%)</span>';
                                        endif;
                                        ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php echo number_format(round($prixVente * (100 + $v->getArticleTauxTVA()) / 100, 2), 2, ',', ' ') . '€'; ?>
                                    </td>
                                </tr>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </tbody>
                </table>

            </div>
        </div>

    </div>
</div>

<?php
include('formProduit.php');
?>