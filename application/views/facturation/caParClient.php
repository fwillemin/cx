<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-sm-10 col-sm-offset-1 baseCX">
            <?php include('ssMenuPeriode.php'); ?>
        </div>
    </div>
    <div class="row hidden-xs" style="margin-top:10px;">

        <div class="col-sm-6 col-sm-offset-3 baseCX">
            <h3>Chiffre d'affaires clients particuliers sur la période : <strong><?= number_format($totalParticuliers, 2, ',', ' ') . '€'; ?></strong></h3>
            <h2>Chiffre d'affaires cumulé par client PRO</h2>
            <table class="table table-condensed table-bordered" style="font-size: 13px; background-color: #FFF;">
                <thead>
                    <tr style="background-color: #04335a; color: #FFF;">
                        <th style="width: 30px;">ID Client</th>
                        <th style="width: 380px;">Raison sociale</th>
                        <th style="text-align: right; width: 120px;">CA HT</th>
                    <tr>
                </thead>
                <tbody>
                    <?php
                    $totalPros = 0;
                    if ($chiffres):
                        foreach ($chiffres as $c):
                            $totalPros += $c->chiffreAffaire;
                            ?>
                            <tr>
                                <td><?= $c->clientId; ?></td>
                                <td><?= $c->raisonSociale; ?></td>
                                <td style="text-align: right;"><?= number_format($c->chiffreAffaire, 2, ',', ' '); ?></td>
                            </tr>
                            <?php
                        endforeach;
                    endif;
                    ?>
                    <tr>
                        <td colspan="2" align="right">Total HT</td>
                        <td style="text-align: right;"><?= number_format($totalPros, 2, ',', ' '); ?></td>
                    </tr>
                </tbody>
            </table>

        </div>

    </div>
</div>
