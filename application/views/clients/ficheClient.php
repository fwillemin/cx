<div class="container">
    <div class="row baseCX" style="margin-top:10px;">
        <div class="col-sm-12">
            <h3>
                <?php
                if ($client->getClientRaisonSociale()) :
                    echo $client->getClientRaisonSociale() . ' / ';
                endif;
                echo $client->getClientNom() . ' ' . $client->getClientPrenom();
                ?>
            </h3>

            <div class="row" style="margin-top:5px;">
                <div class="col-sm-4">
                    <address>
                        <?php
                        echo $client->getClientAdresse1();
                        if ($client->getClientAdresse2()):
                            echo '<br>' . $client->getClientAdresse2();
                        endif;
                        echo '<br>' . $client->getClientCp() . ' ' . $client->getClientVille() . '<br>'
                        . '<i class="glyphicon glyphicon-phone-alt"></i> ' . $client->getClientTel() . '<br>'
                        . '<i class="glyphicon glyphicon-phone"></i> ' . $client->getClientPortable() . '<br>'
                        . '<i class="glyphicon glyphicon-enveloppe"></i> <a href="mailto:' . $client->getClientEmail() . '">' . $client->getClientEmail() . '</a>';
                        ?>
                    </address>
                </div>
                <div class="col-sm-4">
                    Mode de réglement :
                    <?php
                    if ($client->getClientModeReglementId() > 0):
                        echo $client->getClientModeReglement()->getModeReglementNom();
                    endif;
                    echo '<br>Conditions de réglement : ' . $client->getClientConditionReglement()->getConditionReglementNom() .
                    '<br>Code comptable : ' . $client->getClientCodeComptable();
                    ?>
                </div>
                <div class="col-sm-4" style="text-align: right;">
                    <div class="btn-group btn-sm" style="position:relative; top:-55px;">
                        <button class="btn btn-warning tooltipOk" data-placement="left" title="Modifier le client" id="btnModClient" cible="<?php echo $client->getClientId(); ?>"><i class="fas fa-pencil-alt"></i> Modifier</button>
                        <button <?php if (!empty($bdcs) && count($bdcs) > 0) echo 'disabled'; ?>  class="btn btn-danger tooltipOk" data-placement="bottom" title="Double-click pour supprimer le client et ses devis" id="btnDelClient" cible="<?php echo $client->getClientId(); ?>"><i class="glyphicon glyphicon-erase"></i> Supprimer</button>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top:10px;">
                <div class="col-sm-4">
                    <h3>Devis</h3>
                    <table class="table table-condensed table-bordered" style="font-size: 13px;">
                        <thead>
                        <th>N°</th>
                        <th>Date</th>
                        <th style="text-align: right;">Montant</th>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($devis)):
                                foreach ($devis as $d):
                                    ?>
                                    <tr>
                                        <td><a href="<?php echo site_url('chiffrages/reloadDevis/' . $d->getDevisId()); ?>" target="_blank"><?php echo $d->getDevisId(); ?></a></td>
                                        <td><?php echo date('d/m/Y', $d->getDevisDate()); ?></td>
                                        <td style="text-align: right"><?php echo number_format($d->getDevisTotalTTC(), 2, ',', ' ') . '€'; ?></td>
                                    </tr>
                                    <?php
                                endforeach;
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-4">
                    <h3>Bons de commande</h3>
                    <table class="table table-condensed table-bordered" style="font-size: 13px;">
                        <thead>
                        <th>N°</th>
                        <th>Date</th>
                        <th style="text-align: right;">Montant</th>
                        <th>Suivi</th>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($bdcs)):
                                foreach ($bdcs as $b):
                                    ?>
                                    <tr>
                                        <td><a href="<?php echo site_url('ventes/reloadBdc/' . $b->getBdcId()); ?>" target="_blank"><?php echo $b->getBdcId(); ?></a></td>
                                        <td><?php echo date('d/m/Y', $b->getBdcDate()); ?></td>
                                        <td style="text-align: right;"><?php echo number_format($b->getBdcTotalTTC(), 2, ',', ' ') . '€'; ?></td>
                                        <td>
                                            <?php
                                            switch ($b->getBdcEtat()):
                                                case 2:
                                                    echo '<span class="label label-success">Livré</span>';
                                                    break;
                                                case 1:
                                                    echo '<span class="label label-warning">Partiellement livré</span>';
                                                    break;
                                                default:
                                                    echo '...';
                                                    break;
                                            endswitch;
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
                <div class="col-sm-4">
                    <h3>Factures</h3>
                    <table class="table table-condensed table-bordered" style="font-size: 13px;">
                        <thead>
                        <th>N°</th>
                        <th>Date</th>
                        <th style="text-align: right;">Montant</th>
                        <th style="text-align: right;">Solde</th>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($factures)):
                                foreach ($factures as $f):
                                    if ($f->getFactureDelete() == 1):
                                        $style = 'style="color: white; background-color: orangered;"';
                                    else:
                                        $style = '';
                                    endif;
                                    ?>
                                    <tr <?= $style; ?> >
                                        <td><a href="<?php echo site_url('factures/ficheFacture/' . $f->getFactureId()); ?>" target="_blank"><?php echo $f->getFactureId(); ?></a></td>
                                        <td><?php echo date('d/m/Y', $f->getFactureDate()); ?></td>
                                        <td style="text-align: right;"><?php echo number_format($f->getFactureTotalTTC(), 2, ',', ' ') . '€'; ?></td>
                                        <td style="text-align: right;"><?php echo number_format($f->getFactureSolde(), 2, ',', ' ') . '€'; ?></td>
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
    <?php include('formClient.php'); ?>
</div>