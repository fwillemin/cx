<?php include('ssMenuFacturation.php'); ?>
<?php include('ssMenuPeriode.php'); ?>

<div class="container-fluid">
    <div class="row hidden-xs" style="margin-top:10px;">
        <div class="col-sm-4 col-sm-offset-2 baseCX">

            <a href="<?= site_url('facturation/feuilleDeCaisse/' . $debut . '/' . $fin); ?>" target="_blank" class="btn btn-default pull-right">
                <i class="glyphicon glyphicon-print"></i>
            </a>
            <h2>Feuille de caisse</h2>
            <?php if (!empty($caisse)): ?>

                <table class="table table-condensed table-striped" id="tableCaisse">
                    <thead>
                    <th>Date</th>
                    <th>Objet</th>
                    <th style="text-align: right;">Montant</th>
                    <th style="text-align: right;">Solde</th>
                    <th></th>
                    </thead>
                    <tbody>
                        <?php
                        $solde = 0;
                        foreach ($caisse as $c):
                            switch ($c['origine']):
                                case 'caisse':
                                    if ($c['type'] == 1): /* sortie de caisse */
                                        $solde -= $c['montant'];
                                    else:
                                        if ($c['montant'] != $solde && $c['date'] >= $debut): /* génération d'un écart */
                                            echo '<tr style="color:#a94442; background-color:#f2dede;"><td colspan="5" style="text-align:center;">Ecart constaté : ' . number_format(round($solde - $c['montant'], 2), 2, ',', ' ') . '€</td></tr>';
                                        endif;
                                        $solde = $c['montant'];
                                    endif;
                                    break;
                                case 'reglement':
                                    $solde += $c['montant'];
                                    break;
                                case 'acompte':
                                    $solde += $c['montant'];
                                    break;
                            endswitch;
                            if ($c['date'] >= $debut):
                                ?>
                                <tr>
                                    <td width="80"><?php echo date('d/m/Y', $c['date']); ?></td>
                                    <td>
                                        <?php
                                        if ($c['origine'] == 'caisse'):
                                            if ($c['type'] == 1)
                                                $objet = '<strong>Sortie de caisse</strong><br>' . $c['objet'];
                                            else
                                                $objet = 'Fond de caisse';
                                        elseif ($c['origine'] == 'acompte'):
                                            $objet = 'Acompte BDC ' . $c['objet'];
                                        else:
                                            $objet = $c['type'] . ' ' . $c['objet'];
                                        endif;
                                        echo $objet;
                                        ?>
                                    </td>
                                    <td style="text-align: right;"><?php echo number_format($c['montant'], 2, ',', ' ') . '€'; ?></td>
                                    <td style="text-align: right;"><?php echo number_format($solde, 2, ',', ' ') . '€'; ?></td>
                                    <td width="40">
                                        <?php if (intval($c['type']) > 0): ?>
                                            <button class="btn btn-xs btn-link btnModCaisse" cible="<?php echo $c['id']; ?>"><i class="fas fa-pencil-alt"></i></button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php
                            endif;
                        endforeach;
                        ?>
                    </tbody>
                </table>
                <?php
            else: echo 'Veuillez saisir un fond de caisse pour utiliser ce module';
            endif;
            ?>

        </div>

        <div class="col-sm-3">

            <div class="row" style="margin-left:10px; background-color: #FFF; border:1px solid black; padding: 5px;">
                <div class="col-sm-12">
                    <h3 id="titreActionCaisse">Ajouter un mouvement de caisse</h3>
                    <?php echo form_open('facturation/addMouvementCaisse', array('class' => 'form-horizontal', 'id' => 'formAddMouvementCaisse')); ?>
                    <input type="hidden" name="addCaisseId" id="addCaisseId" value="" >
                    <div class="form-group">
                        <label for="addCaisseDate" class="col-sm-4">Date</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" name="addCaisseDate" id="addCaisseDate" value="<?php echo date('Y-m-d'); ?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="addCaisseType" class="col-sm-4">Type</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="addCaisseType" id="addCaisseType">
                                <option value="1">Sortie</option>
                                <option value="2">Fond de caisse</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="addCaisseMontant" class="col-sm-4">Montant</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="text" class="form-control" name="addCaisseMontant" id="addCaisseMontant" value="" >
                                <span class="input-group-addon">€</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="addCaisseDetail" class="col-sm-4">Détail</label>
                        <div class="col-sm-8">
                            <textarea rows="3" class="form-control" name="addCaisseDetail" id="addCaisseDetail"></textarea>
                        </div>
                    </div>
                    <button class="btn btn-warning" type="submit" id="btnSubmitAddMouvementCaisse" style="width: 100%;">Ajouter</button>
                    <?php echo form_close(); ?>
                    <br>
                    <div class="row">
                        <div class="col-sm-6">
                            <button class="btn btn-sm btn-default" id="btnAbortAddMouvementCaisse" style="display:none;">Annuler la modification</button>
                        </div>
                        <div class="col-sm-6">
                            <button class="btn btn-danger btn-sm tooltipOk pull-right" data-placement="left" title="Double-click pour supprimer" id="btnDelCaisse" style="display:none;"><i class="glyphicon glyphicon-erase"></i> Supprimer le mouvement</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

