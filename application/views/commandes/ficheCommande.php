<?php include('ssMenuCommande.php'); ?>
<div class="row baseCX" style="margin-top:5px;">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <a class="btn btn-default" style="position:absolute; top:10px; right:10px; z-index:10;" href="<?php echo site_url('commandes/bdcFournisseur/' . $commande->commandeId); ?>"><i class="glyphicon glyphicon-file"></i> Bon de commande</a>
        <h3>Commande N° <?php echo $commande->commandeId . ' du ' . date('d/m/Y', $commande->commandeDate) . ' Usine : ' . $commande->usine; ?></h3>
        Bons de commande concernés :
        <?php
        if ($bdc):
            foreach ($bdc as $b):
                ?>
                <a href="<?php echo site_url('ventes/reloadBdc/' . $b->bdcId); ?>" target="_blank"><?php echo $b->clientNom . ' ' . $b->clientPrenom; ?></a>,
                <?php
            endforeach;
        endif;
        ?>

        <table class="table table-condensed" style="font-size:12px; margin-top:20px;">
            <thead>
            <th>N° Appro</th>
            <th>Ref Produit</th>
            <th>Designation</th>
            <th>Qte</th>
            <th>Unité</th>
            <th style="text-align: right;">Récéption</th>
            </thead>
            <tbody>
                <?php
                if ($appros):
                    foreach ($appros as $a):
                        ?>
                        <tr id="<?php echo $a->approId; ?>">
                            <td><?php echo $a->approId; ?></td>
                            <td><?php echo $a->approProduitId; ?></td>
                            <td><a href="<?php echo site_url('produits/ficheProduit/' . $a->approProduitId); ?>"><?php echo $a->produitDesignation; ?></a></td>
                            <td><?php echo number_format($a->approQte, 2, ',', ' '); ?></td>
                            <td><?php echo $this->cxwork->affUnite($a->produitUniteId); ?></td>
                            <td style="text-align: right;">
                                <?php
                                $recu = 0; /* qte deja recue pour cet appro */
                                if ($receptions):
                                    foreach ($receptions as $r):
                                        if ($r->receptionApproId == $a->approId):
                                            $recu += $r->receptionQte;
                                            echo '<span style="color:green;">' . $r->receptionQte . ' reçu(s) le ' . date('d/m/Y', $r->receptionDate) . ' (Bain : ' . $r->receptionBain . ' Calibre : ' . $r->receptionCalibre . ')<br/>';
                                        endif;
                                    endforeach;
                                endif;
                                if ($recu < $a->approQte):

                                    echo form_open('commande/receptionAppro/', array('class' => 'form-inline formReceptionCommande'));
                                    ?>
                                    <input type ="hidden" name="receptionApproId" value="<?php echo $a->approId; ?>" />
                                    <div class="input-group">
                                        <input  style="width:70px;" type="text" name="receptionQte" class="form-control" value="<?php echo ($a->approQte - $recu); ?>" />
                                        <span class="input-group-addon"><?php echo $this->cxwork->affUnite($a->produitUniteId); ?></span>
                                    </div>
                                    <input style="width:80px;" type="text" name="receptionBain" class="form-control" value="" placeholder="Bain" <?php if ($a->produitGestionBain == 0) echo 'disabled'; ?> />
                                    <input style="width:80px;" type="text" name="receptionCalibre" class="form-control" value="" placeholder="Calibre" <?php if ($a->produitGestionBain == 0) echo 'disabled'; ?> />
                                    <button class="btn btn-success btn-sm" type="submit">Réceptionner</button>
            <?php
            echo form_close();

        endif;
        ?>
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