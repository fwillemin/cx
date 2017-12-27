<div class="container-fluid">
    <div class="row" id="ventesNew">

        <div class="baseCX col-sm-8 col-sm-offset-2">
            <h2>Paramètrage de votre CX</h2>
            <div class="row">
                <div class="col-xs-8">
                    <table class="table table-condensed table-bordered">
                        <?= form_open('settings/majPdv', array('class' => 'form-horizontal', 'id' => 'formPdv')); ?>
                        <tbody>
                            <tr>
                                <td style="vertical-align: middle; width: 200px;">Raison sociale</td>
                                <td><?= $pdv->getPdvRaisonSociale(); ?></td>
                            </tr>
                            <tr style="background-color: #002166; color: #FFF; font-weight: bold;">
                                <td colspan="2">
                                    Général
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle;">Nom commercial</td>
                                <td>
                                    <input type="text" name="modPdvNomCommercial" class="form-control input-sm" value="<?= $pdv->getPdvNomCommercial(); ?>">
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle;">Siren</td>
                                <td>
                                    <input type="text" name="modPdvSiren" class="form-control input-sm" value="<?= $pdv->getPdvSiren(); ?>">
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle;">Numéro de TVA Intracom</td>
                                <td>
                                    <input type="text" name="modPdvTvaIntracom" class="form-control input-sm" value="<?= $pdv->getPdvTvaIntracom(); ?>">
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle;">APE/NAF</td>
                                <td>
                                    <input type="text" name="modPdvApe" class="form-control input-sm" value="<?= $pdv->getPdvApe(); ?>">
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle;">Adresse</td>
                                <td>
                                    <input type="text" name="modPdvAdresse1" class="form-control input-sm" value="<?= $pdv->getPdvAdresse1(); ?>">
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle;">Complement d'adresse</td>
                                <td>
                                    <input type="text" name="modPdvAdresse2" class="form-control input-sm" value="<?= $pdv->getPdvAdresse2(); ?>">
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle;">Code postal</td>
                                <td>
                                    <input type="text" name="modPdvCp" class="form-control input-sm" value="<?= $pdv->getPdvCp(); ?>">
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle;">Ville</td>
                                <td>
                                    <input type="text" name="modPdvVille" class="form-control input-sm" value="<?= $pdv->getPdvVille(); ?>">
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle;">Téléphone</td>
                                <td>
                                    <input type="text" name="modPdvTelephone" class="form-control input-sm" value="<?= $pdv->getPdvTelephone(); ?>">
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle;">Email</td>
                                <td>
                                    <input type="text" name="modPdvEmail" class="form-control input-sm" value="<?= $pdv->getPdvEmail(); ?>">
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle;">Fax</td>
                                <td>
                                    <input type="text" name="modPdvFax" class="form-control input-sm" value="<?= $pdv->getPdvFax(); ?>">
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle;">Site internet</td>
                                <td>
                                    <input type="text" name="modPdvWww" class="form-control input-sm" value="<?= $pdv->getPdvWww(); ?>">
                                </td>
                            </tr>
                            <tr style="background-color: #002166; color: #FFF; font-weight: bold;">
                                <td colspan="2">
                                    Service commercial
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle;">Téléphone</td>
                                <td>
                                    <input type="text" name="modPdvTelephoneCommercial" class="form-control input-sm" value="<?= $pdv->getPdvTelephoneCommercial(); ?>">
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle;">Email</td>
                                <td>
                                    <input type="text" name="modPdvEmailCommercial" class="form-control input-sm" value="<?= $pdv->getPdvEmailCommercial(); ?>">
                                </td>
                            </tr>
                            <tr style="background-color: #002166; color: #FFF; font-weight: bold;">
                                <td colspan="2">
                                    Service technique
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle;">Téléphone</td>
                                <td>
                                    <input type="text" name="modPdvTelephoneTechnique" class="form-control input-sm" value="<?= $pdv->getPdvTelephoneTechnique(); ?>">
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: middle;">Email</td>
                                <td>
                                    <input type="text" name="modPdvEmailTechnique" class="form-control input-sm" value="<?= $pdv->getPdvEmailTechnique(); ?>">
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" style="text-align: center;">
                                    <button type="submit" class="btn btn-primary">
                                        Modifier les informations
                                    </button>
                                </td>
                            </tr>
                        </tfoot>
                        <?= form_close(); ?>
                    </table>
                </div>
                <div class="col-xs-4">
                    <br><img src="<?= base_url('assets/logos/' . $this->session->userdata('loggedPdvId') . '.png'); ?>">
                </div>
            </div>

        </div>
    </div>
</div>