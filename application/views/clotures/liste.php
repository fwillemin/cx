<div class="container" style="min-height: 1100px;">
    <div class="row" style="">
        <div class="col-xs-3">
            <table class="table table-condensed" style="font-size:13px; border: 2px solid #12223c;">
                <thead>
                    <tr>                        
                        <td style="width: 30px; text-align: center;"><i class="fa fa-certificate"></td>
                        <td>Périodes</td>
                        <td style="text-align: right;">Montant</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!$this->uri->segment(3)):
                        echo '<tr class="clotureAnnee" data-annee="' . date('Y', time()) . '">'
                        . '<td></td>'
                        . '<td colspan="2">Année ' . date('Y', time()) . '</td>'
                        . '</tr>';
                        if ($clotures):
                            foreach ($clotures as $c):
                                ?>
                                <tr class="clotureAnnee" data-annee="<?= date('Y', $c->getClotureDate()); ?>">
                                    <td><?= $c->getClotureSecure() ? '<i class="fa fa-certificate" style="color:green;"></i>' : '<i class="fa fa-warning" style="color:red;"></i>'; ?></td>
                                    <td><?= 'Année ' . date('Y', $c->getClotureDate()); ?></td>
                                    <td style="text-align: right;">
                                        <?= number_format($c->getClotureMontant(), 2, ',', ' ') . '€'; ?>
                                    </td>
                                </tr>
                                <?php
                            endforeach;
                        endif;
                    elseif (!$this->uri->segment(4)):
                        echo '<tr style="background-color: lightgreen;">'
                        . '<td><a href="' . site_url('clotures/liste') . '"><i class="fa fa-chevron-left"></i></a></td>'
                        . '<td colspan="2">Année ' . $this->uri->segment(3) . '</td>'
                        . '</tr>';
                        if ($clotures):
                            foreach ($clotures as $c):
                                ?>
                                <tr>                                    
                                    <td><?= $c->getClotureSecure() ? '<i class="fa fa-certificate" style="color:green;"></i>' : '<i class="fa fa-warning" style="color:red;"></i>'; ?></td>
                                    <td class="clotureMois" data-mois="<?= $this->uri->segment(3) . '/' . date('m', $c->getClotureDate()); ?>">
                                        <?= date('M', $c->getClotureDate()); ?>
                                    </td>
                                    <td style="text-align: right;">
                                        <?= number_format($c->getClotureMontant(), 2, ',', ' ') . '€'; ?>
                                    </td>
                                </tr>
                                <?php
                            endforeach;
                        endif;
                        echo '<tr>'
                        . '<td></td>'
                        . '<td class="clotureMois" data-mois="' . $this->uri->segment(3) . '/' . date('m', time()) . '">' . date('M', time()) . '</td>'
                        . '<td></td>'
                        . '</tr>';
                    else:
                        echo '<tr style="background-color: lightgreen;">'
                        . '<td><a href="' . site_url('clotures/liste') . '"><i class="fa fa-chevron-left"></i></a></td>'
                        . '<td colspan="2">Année ' . $this->uri->segment(3) . '</td>'
                        . '</tr><tr style="background-color: lightgreen;">'
                        . '<td><a href="' . site_url('clotures/liste/' . $this->uri->segment(3)) . '"><i class="fa fa-chevron-left"></i></a></td>'
                        . '<td colspan="2">' . $this->letslib->aff_mois($this->uri->segment(4)) . '</td>'
                        . '</tr>';
                        if ($clotures):
                            foreach ($clotures as $c):
                                if( date('d', $c->getClotureDate()) == $this->uri->segment(5) ):
                                    $bgStyle = 'background-color: gold;';
                                else:
                                    $bgStyle = '';
                                endif; ?>
                                <tr class="clotureJour" data-jour="<?= $this->uri->segment(3) . '/' . $this->uri->segment(4) . '/' . date('d', $c->getClotureDate()); ?>" style="<?= $bgStyle; ?>">                                    
                                    <td>
                                        <?= $c->getClotureSecure() ? '<i class="fa fa-certificate" style="color:green;"></i>' : '<i class="fa fa-warning" style="color:red;"></i>'; ?>
                                    </td>
                                    <td>
                                        <?= date('d', $c->getClotureDate()); ?>
                                    </td>
                                    <td style="text-align: right;">
                                        <?= number_format($c->getClotureMontant(), 2, ',', ' ') . '€'; ?>
                                    </td>
                                </tr>
                                <?php
                            endforeach;
                        endif;
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
        <div class="col-xs-6">
            <table class="table table-bordered table-condensed" style="font-size:13px; border: 2px solid #12223c;">
                <thead>
                    <tr style="background-color: #12223c; color: #FFF;">
                        <th style="text-align: center; width: 30px;">
                            <i class="fa fa-certificate"></i>
                        </th>
                        <th style="text-align: center; width: 60px;">Heure</th>
                        <th style="width: 150px;">Client</th>
                        <th style="text-align: right;">Total TTC</th>
                        <th style="text-align: right;">Espèces</th>
                        <th style="text-align: right;">Chèque</th>
                        <th style="text-align: right;">Carte B.</th>
                    </tr>
                </thead>
                <?php
                if (!empty($ventes)):
                    foreach ($ventes as $v):
                        ?>
                        <tr class="ligneVente" data-venteid="<?= $v->getVenteId(); ?>">
                            <td style="text-align: center;">
                                <?= $v->getVenteReglement()->getReglementSecure() ? '<i class="fa fa-certificate" style="color:green;"></i>' : '<i class="fa fa-warning" style="color:red;"></i>'; ?>
                            </td>
                            <td style="text-align: center;"><?= date('H:i', $v->getVenteDate()); ?></td>
                            <td><?= $v->getVenteClient()->getClientNom(); ?></td>
                            <td style="text-align: right;"><?= number_format($v->getVenteTotalTTC(), 2, ',', ' '); ?>€</td>
                            <td style="text-align: right;"><?= $v->getVenteReglement()->getReglementEspeces() > 0 ? number_format($v->getVenteReglement()->getReglementEspeces(), 2, ',', ' ') . '€' : ''; ?></td>
                            <td style="text-align: right;"><?= $v->getVenteReglement()->getReglementCheque() > 0 ? number_format($v->getVenteReglement()->getReglementCheque(), 2, ',', ' ') . '€' : ''; ?></td>
                            <td style="text-align: right;"><?= $v->getVenteReglement()->getReglementCb() > 0 ? number_format($v->getVenteReglement()->getReglementCb(), 2, ',', ' ') . '€' : ''; ?></td>                    
                        </tr>
                        <?php
                    endforeach;
                endif;
                ?>
            </table>
        </div>
        <div class="col-xs-3" style="text-align: right;">
            <div id="clotureTicket" style="border : 1px solid grey; padding: 5px;" class="pull-right">

            </div>
        </div>
    </div>
</div>
