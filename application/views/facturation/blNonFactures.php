<?php //include('ssMenuFacturation.php');  ?>
<div class="container-fluid">
    <div class="row">
        <div class="baseCX col-sm-6 col-sm-offset-3">

            <table class="table" style="font-size:12px;">
                <thead>
                <th>BL</th>
                <th>Date</th>
                <th>Bdc</th>
                <th>Facturer</th>
                </thead>
                <tbody>
                    <?php
                    $client = 0;
                    if (!empty($bls)):
                        foreach ($bls as $b):
                            if ($client != $b->getBlClientId()):
                                ?>
                                <tr id="<?= $b->getBlClientId(); ?>" class="alert alert-warning blNonFacturesClientSelect" style="cursor:pointer;">
                                    <td colspan="3">
                                        <?= $b->getBlClient()->getClientNom(); ?>
                                        <span style="color: grey; font-size:10px;">
                                            Mode de réglement : <strong>
                                                <?php
                                                if ($b->getBlClient()->getClientModeReglementId() > 0):
                                                    echo $b->getBlClient()->getClientModeReglement()->getModeReglementNom();
                                                else:
                                                    echo 'Non défini';
                                                endif;
                                                ?>
                                            </strong>, Conditions de réglement : <strong><?= $b->getBlClient()->getClientConditionReglement()->getConditionReglementNom(); ?></strong>
                                        </span>

                                        <button class="btn btn-success btn-xs tooltipOk pull-right btnAddFacture <?php echo $b->getBlClientId(); ?>" style="display:none;" data-placement="left" title="Double-click pour facturer">
                                            <i class="glyphicon glyphicon-list-alt"></i> Facturer
                                        </button>
                                    </td>
                                </tr>
                                <?php
                            endif;
                            $client = $b->getBlClientId();
                            ?>
                            <tr>
                                <td><button class="btn btn-link btn-xs btnAffBl"><?php echo $b->getBlId(); ?></button></td>
                                <td><?= date('d/m/Y', $b->getBlDate()); ?></td>
                                <td><a href="<?= site_url('ventes/reloadBdc/' . $b->getBlBdcId()); ?>" target="_blank"><?= $b->getBlBdcId(); ?></a></td>
                                <td><input type="checkbox" class="blAFacturer <?php echo $client; ?>" style="display:none;" value="<?php echo $b->getBlId(); ?>" /></td>
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

<div class="row visible-xs">
    <div class="col-xs-12">
        <div class="alert alert-warning"><i class="glyphicon glyphicon-alert"></i> Cette section n'est pas accéssible depuis un smartphone.</div>
    </div>
</div>