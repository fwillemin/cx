<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-sm-10 col-sm-offset-1 baseCX">
            <?php include('ssMenuPeriode.php'); ?>
        </div>
    </div>
    <div class="row hidden-xs" style="margin-top:10px;">

        <div class="col-sm-5 col-sm-offset-1 baseCX">
            <a href="<?= site_url('documents/journalDesVentes/' . $debut . '/' . $fin); ?>" target="_blank" class="btn btn-default pull-right">
                <i class="fas fa-print"></i>
            </a>
            <h2>Chiffre d'affaires</h2>
            <table class="table table-condensed table-bordered" style="font-size: 13px; background-color: #FFF;">
                <thead>
                    <tr style="background-color: #04335a; color: #FFF;">
                        <th style="width: 100px;">Date</th>
                        <th style="width: 100px;">Fact</th>
                        <th>Client</th>
                        <th style="text-align: right; width: 100px;">Total HT</th>
                        <th style="text-align: right; width: 100px;">TVA</th>
                    <tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($factures)):
                        $totalHTPeriode = 0;
                        $totalTVAPeriode = 0;
                        foreach ($factures as $f):
                            $totalHTPeriode += $f->getFactureTotalHT();
                            $totalTVAPeriode += $f->getFactureTotalTVA();
                            ?>
                            <tr>
                                <td><?= date('d/m/Y', $f->getFactureDate()); ?></td>
                                <td><a href="<?= site_url('factures/ficheFacture/' . $f->getFactureId()); ?>" target="_blank"><?= $f->getFactureId(); ?></a></td>
                                <td>
                                    <?php
                                    if ($f->getFactureClient()->getClientType() == 1):
                                        echo $f->getFactureClient()->getClientNom();
                                    else:
                                        echo $f->getFactureClient()->getClientRaisonSociale();
                                    endif;
                                    ?>
                                </td>
                                <td style="text-align: right;"><?= number_format($f->getFactureTotalHT(), 2, ',', ' '); ?></td>
                                <td style="text-align: right;"><?= number_format($f->getFactureTotalTVA(), 2, ',', ' '); ?></td>
                            </tr>
                            <?php
                        endforeach;
                    endif;
                    if ($avoirs):
                        echo '<tr><td colspan="5" style="font-weight: bold; background-color:gold;">Avoirs de la période</td></tr>';
                        foreach ($avoirs as $a):
                            $totalHTPeriode -= $a->getAvoirTotalHT();
                            $totalTVAPeriode -= $a->getAvoirTotalTVA();
                            ?>
                            <tr>
                                <td><?= date('d/m/Y', $a->getAvoirDate()); ?></td>
                                <td><a href="<?= site_url('factures/ficheFacture/' . $a->getAvoirFactureId()); ?>" target="_blank"><?= $a->getAvoirId(); ?></a></td>
                                <td>
                                    <?php
                                    if ($a->getAvoirClient()->getClientType() == 1):
                                        echo $a->getAvoirClient()->getClientNom();
                                    else:
                                        echo $a->getAvoirClient()->getClientRaisonSociale();
                                    endif;
                                    ?>
                                </td>
                                <td style="text-align: right;"><?= '-' . number_format($a->getAvoirTotalHT(), 2, ',', ' '); ?></td>
                                <td style="text-align: right;"><?= '-' . number_format($a->getAvoirTotalTVA(), 2, ',', ' '); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3" style="text-align: right; font-weight: bold;">Totaux</td>
                            <td style="text-align: right; width:80px; font-weight: bold;"><?= number_format($totalHTPeriode, 2, ',', ' ') . '€'; ?></td>
                            <td style="text-align: right; width:80px; font-weight: bold;"><?= number_format($totalTVAPeriode, 2, ',', ' ') . '€'; ?></td>
                        </tr>
                    <?php endif;
                    ?>
                </tbody>
            </table>

        </div>

        <div class="col-sm-5 baseCX">
            <a href="<?= site_url('documents/journalDesEncaissements/' . $debut . '/' . $fin); ?>" target="_blank" class="btn btn-default pull-right">
                <i class="fas fa-print"></i>
            </a>
            <h2>Encaissements</h2>
            <table class="table table-condensed table-bordered" style="font-size: 13px; background-color: #FFF;">
                <thead>
                    <tr style="background-color: #04335a; color: #FFF;">
                        <th style="width:20px;"></th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Fact</th>
                        <th>Client</th>
                        <th>Total</th>
                        <th>Mode</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalCB = 0;
                    $totalCHE = 0;
                    $totalESP = 0;
                    $totalVIR = 0;
                    $totalTRA = 0;
                    if (!empty($reglements)):
                        foreach ($reglements as $r):
                            switch ($r->getReglementModeId()):
                                case 1 :
                                    $totalCB += $r->getReglementMontant();
                                    break;
                                case 2 :
                                    $totalCHE += $r->getReglementMontant();
                                    break;
                                case 3 :
                                    $totalESP += $r->getReglementMontant();
                                    break;
                                case 4 :
                                    $totalTRA += $r->getReglementMontant();
                                    break;
                                case 5 :
                                    $totalVIR += $r->getReglementMontant();
                                    break;
                            endswitch;
                            ?>
                            <tr>
                                <td>
                                    <?php
                                    if ($r->getReglementSecure()):
                                        if ($r->getReglementHistorique()):
                                            echo '<i class = "fas fa-copy" style = "color: purple;"></i>';
                                        else:
                                            echo '<i class = "fas fa-certificate" style = "color: green;"></i>';
                                        endif;
                                    else:
                                        echo '<i class = "fas fa-exclamation-triangle" style = "color: red;"></i>';
                                    endif;
                                    ?>
                                </td>
                                <td><?= date('d/m/Y', $r->getReglementDate()); ?></td>
                                <td><?= $r->getReglementType() == 1 ? 'Acompte' : 'Solde'; ?></td>
                                <td>
                                    <a href="<?= site_url('factures/ficheFacture/' . $r->getReglementFactureId()); ?>">
                                        <?= $r->getReglementFactureId(); ?>
                                    </a>
                                </td>
                                <td>
                                    <a href="<?= site_url('clients/ficheClient/' . $r->getreglementClientId()); ?>" target="_blank">
                                        <?php
                                        if ($r->getReglementClient()->getClientType() == 1):
                                            echo $r->getReglementClient()->getClientNom();
                                        else:
                                            echo $r->getReglementClient()->getClientRaisonSociale();
                                        endif;
                                        ?>
                                    </a>
                                </td>
                                <td><?= number_format($r->getReglementMontant(), 2, ',', ' ') . '€'; ?></td>
                                <td><?= $r->getReglementMode()->getModeReglementNom(); ?></td>
                            </tr>
                            <?php
                        endforeach;
                    endif;
                    ?>
            </table>

            <h4>Totaux de la période</h4>
            <table class="table table-condensed table-bordered table-striped">
                <tr>
                    <td rowspan="5" style="width: 70%;">
                        <div class="row">
                            <div class="col-sm-12">
                                <canvas id="graphEncaissements" style="height: 150px; width: 200px; margin: 0 auto">

                                </canvas>
                            </div>
                        </div>
                    </td>
                    <td>Espèces</td>
                    <td style="text-align:right;"><?= number_format($totalESP, 2, ',', ' ') . '€'; ?></td>
                </tr>
                <tr>
                    <td>Chèques</td>
                    <td style="text-align:right;"><?= number_format($totalCHE, 2, ',', ' ') . '€'; ?></td>
                </tr>
                <tr>
                    <td>CB</td>
                    <td style="text-align:right;"><?= number_format($totalCB, 2, ',', ' ') . '€'; ?></td>
                </tr>
                <tr>
                    <td>Virement</td>
                    <td style="text-align:right;"><?= number_format($totalVIR, 2, ',', ' ') . '€'; ?></td>
                </tr>
                <tr>
                    <td>Traite</td>
                    <td style="text-align:right;"><?= number_format($totalTRA, 2, ',', ' ') . '€'; ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
