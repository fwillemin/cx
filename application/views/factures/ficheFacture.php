<div class="container-fluid">
    <div class="row hidden-xs">

        <div class="baseCX col-sm-10 col-sm-offset-1">
            <div class="row">
                <div class="col-sm-9">
                    <h2 style="margin:0px;"><a href="<?= site_url('documents/editionFacture/' . $facture->getFactureId()); ?>" target="_blank"><i class="fas fa-file-pdf" style="color:<?= $facture->getFactureSolde() > 0 ? 'orangered' : "green"; ?>;"></i></a> Détails de la facture <?= $facture->getFactureId(); ?></i></h2>
                </div>
                <div class="col-sm-3" style="text-align: right; font-size: 15px;">
                    <strong>Date : <?= date('d/m/Y', $facture->getFactureDate()); ?></strong>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-xs-12 col-sm-2">
                    <?php $client = $facture->getFactureClient(); ?>
                    <span style="font-weight: bold; font-size:16px;"><?php
                        echo $client->getClientRaisonSociale() ?: '';
                        $client->getClientNom() ? '<br>' . $client->getClientNom() . ' ' . $client->getClientPrenom() : '';
                        ?></span>
                    <?php
                    echo '<br>' . $client->getClientAdresse1();
                    echo $client->getClientAdresse2() ? '<br>' . $client->getClientAdresse2() : '';
                    echo '<br>' . $client->getClientCp() . ' ' . $client->getClientVille();
                    echo '<br>' . $client->getClientPays();
                    ?>
                    <hr>
                    <button class="btn btn-link" id="sendFactureByEmail" data-factureid="<?= $facture->getFactureId(); ?>">
                        <i class="fas fa-envelope"></i> Renvoyer par mail
                    </button>
                    <a href="<?= site_url('factures/genererAvoir/' . $facture->getFactureId()); ?>" class="btn btn-link"><i class="fas fa-undo"></i> Générer un avoir</a>
                    <hr><strong>Solde : <span style="color:<?= $facture->getFactureSolde() > 0 ? 'orangered' : "green"; ?>;"><?= number_format($facture->getFactureSolde(), 2, ',', ' ') . '€'; ?></span></strong>
                    <br>
                    <?php
                    if ($facture->getFactureSolde() != 0):
                        echo '<a class="btn btn-link" href="' . site_url('factures/forceSolde/' . $facture->getFactureId() . '/0') . '"><i class="fas fa-arrow-circle-down"></i> Forcer le solde à 0</a>';
                    else:
                        echo '<a class="btn btn-link" href="' . site_url('factures/recalculeSolde/' . $facture->getFactureId()) . '"><i class="fas fa-question-circle"></i> Recalcul du solde</a>';
                    endif;
                    ?>
                    <br><br>Total HT : <?= number_format($facture->getFactureTotalHT(), 2, ',', ' ') . '€'; ?>

                    <br>Total TVA : <?= number_format($facture->getFactureTotalTVA(), 2, ',', ' ') . '€'; ?>
                    <br>Total TTC : <?= number_format($facture->getFactureTotalTTC(), 2, ',', ' ') . '€'; ?>

                </div>
                <div class = "col-xs-12 col-sm-3">
                    <h3>Liens de la facture</h3>
                    <table class="table table-bordered table-condensed" style="font-size: 13px; background-color: #FFF;">
                        <tr style="background-color: #04335a; color: #FFF;">
                            <td style="width:20px;"><i class="fa fa-link"></i></td>
                            <td>Document</td>
                            <td>Info</td>
                            <td style="width:20px;"><i class="fa fa-file-pdf"></td>
                        </tr>
                        <?php
                        $listeBdc = array();
                        foreach ($facture->getFactureBls() as $bl):
                            if (!in_array($bl->getBlBdcId(), $listeBdc)):
                                $listeBdc[] = $bl->getBlBdcId();
                            endif;
                            echo '<tr><td></td><td>Bon de livraison ' . $bl->getBlId() . '</td><td>' . date('d/m/Y', $bl->getBlDate()) . '</td>'
                            . '<td><a href="' . site_url('documents/editionBl/' . $bl->getBlId()) . '" target="_blank"><i class="fas fa-file-pdf"></i></a></td></tr>';
                        endforeach;
                        $listeDevis = array();
                        foreach ($listeBdc as $bdcId):
                            $bdc = $this->managerBdc->getBdcById($bdcId);
                            if (!in_array($bdc->getBdcDevisId(), $listeDevis)):
                                $listeDevis[] = $bdc->getBdcDevisId();
                            endif;
                            switch ($bdc->getBdcEtat()):
                                case 0:
                                    $etat = 'En cours';
                                    break;
                                case 1:
                                    $etat = 'Partiellement livré';
                                    break;
                                case 2:
                                    $etat = 'Livré';
                                    break;
                            endswitch;
                            echo '<tr><td><a href="' . site_url('ventes/reloadBdc/' . $bdc->getBdcId()) . '" target="_blank"><i class="fas fa-link"></i></a></td>'
                            . '<td>Commande ' . $bdc->getBdcId() . '</td><td>' . $etat . '</td>'
                            . '<td><a href="' . site_url('documents/editionBdc/' . $bdc->getBdcId()) . '" target="_blank"><i class="fas fa-file-pdf"></i></a></td></tr>';
                        endforeach;
                        foreach ($listeDevis as $devisId):
                            $devis = $this->managerDevis->getDevisById($devisId);
                            echo '<tr><td><a href="' . site_url('chiffrages/reloadDevis/' . $devis->getDevisId()) . '" target="_blank"><i class="fas fa-link"></i></a></td>'
                            . '<td>Devis ' . $devis->getDevisId() . '</td><td>' . date('d/m/Y', $devis->getDevisDate()) . '</td>'
                            . '<td><a href="' . site_url('documents/editionDevis/' . $devis->getDevisId()) . '" target="_blank"><i class="fas fa-file-pdf"></i></a></td></tr>';
                        endforeach;
                        ?>
                    </table>
                </div>
                <div class="col-xs-12 col-sm-4">
                    <h3>Réglements</h3>
                    <table class="table table-bordered table-condensed" style="font-size: 13px; background-color: #FFF;">
                        <tr style="background-color: #04335a; color: #FFF;">
                            <td></td>
                            <td>Date</td>
                            <td>Type</td>
                            <td>Montant</td>
                            <td>Mode</td>
                            <td></td>
                        <tr>

                            <?php
                            if (!empty($facture->getFactureReglements())):
                                foreach ($facture->getFactureReglements() as $r):
                                    ?>
                                <tr data-reglementid="<?= $r->getReglementId(); ?>" data-reglementMontant="<?= $r->getReglementMontant(); ?>" data-reglementmodeid="<?= $r->getReglementModeId(); ?>">
                                    <td>
                                        <?php
                                        if ($r->getReglementSecure()):
                                            if ($r->getReglementHistorique()):
                                                echo '<i class="fas fa-copy" style="color: purple;"></i>';
                                            else:
                                                echo '<i class="fas fa-certificate" style="color: green;"></i>';
                                            endif;
                                        else:
                                            echo '<i class="fas fa-warning" style="color: red;"></i>';
                                        endif;
                                        ?>
                                    </td>
                                    <td>
                                        <?= date('d/m/Y', $r->getReglementDate()); ?>
                                    </td>
                                    <td>
                                        <?= $r->getReglementType() == 1 ? 'Acompte' : 'Solde'; ?>
                                    </td>
                                    <td style="text-align: right;"><?= number_format($r->getReglementMontant(), 2, ',', ' ') . '€'; ?></td>
                                    <td><?= $r->getReglementMode()->getModeReglementNom(); ?></td>
                                    <td style="text-align: center;">
                                        <?php
                                        if (date('d/m/Y', $r->getReglementDate()) == date('d/m/Y')):
                                            echo '<button class="btn btn-xs btn-link btnModReglement" style="margin:0px; padding:0px;"><i class="fas fa-pencil-alt" style="cursor: pointer;"></i></button>';
                                        endif;
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </table>
                    <?= form_open('factures/addReglement', array('class' => 'form-inline', 'id' => 'formAddReglement'));
                    ?>
                    <input type="hidden" name="addReglementId" id="addReglementId" value="">
                    <strong>Réglement : </strong>
                    <div class="input-group">
                        <input type="text" style="width:80px; text-align: right;" class="form-control input-sm" name="addReglementMontant" id="addReglementMontant" value="<?= $facture->getFactureSolde(); ?>" required >
                        <span class="input-group-addon">€</span>
                    </div>
                    <select class="form-control input-sm" name="addReglementObjet" id="addReglementObjet" >
                        <option value="<?= $facture->getFactureId(); ?>"><?= $facture->getFactureId(); ?></option>
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
                    <?= form_close(); ?>

                </div>
                <div class="col-xs-12 col-sm-3">
                    <h3>Avoirs</h3>
                    <table class="table table-bordered table-condensed" style="font-size: 13px; background-color: #FFF;">
                        <tr style="background-color: #04335a; color: #FFF;">
                            <td style="color: lightgray; width: 20px;"></td>
                            <td>N°</td>
                            <td>Date</td>
                            <td style="text-align: right;">Montant TTC</td>
                            <td style="width: 20px;"></td>
                        <tr>

                            <?php
                            if (!empty($facture->getFactureAvoirs())):
                                foreach ($facture->getFactureAvoirs() as $a):
                                    ?>
                                <tr data-avoirid="<?= $a->getAvoirId(); ?>">
                                    <td>
                                        <?php if (file_exists('assets/Avoir ' . $a->getAvoirId() . '.pdf')): ?>
                                            <button class="btn btn-link btn-xs btnSendAvoirEmail" style="padding: 0px;">
                                                <i class="fas fa-envelope"></i>
                                            </button>
                                        <?php endif;
                                        ?>
                                    </td>
                                    <td>
                                        <?= $a->getAvoirId(); ?>
                                    </td>
                                    <td>
                                        <?= date('d/m/Y', $a->getAvoirDate()); ?>
                                    </td>
                                    <td style="text-align: right;"><?= number_format($a->getAvoirTotalTTC(), 2, ',', ' ') . '€'; ?></td>
                                    <td>
                                        <a href="<?= site_url('documents/editionAvoir/' . $a->getAvoirId()); ?>" target="_blank">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            endforeach;
                        endif;
                        ?>

                    </table>

                </div>
            </div>

        </div>

    </div>
</div>