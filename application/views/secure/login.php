<div class="row" style="margin-top:180px;">
    <div class="col-xs-12 col-sm-4 col-sm-offset-4" style="border:1px solid grey; border-radius:10px; padding:15px;  background-color: #aaa; text-align: center; color: #FFF;">
        <img src="<?= base_url('assets/img/cx.png'); ?>" style="height:50px;" >
        <hr>
        <?= form_open('secure/tryLogin', array('class' => 'form-horizontal', 'id' => 'formLogin')); ?>
        <div class="form-group">
            <label for="loginId" class="col-xs-4">Identifiants</label>
            <div class="col-xs-6">
                <input type="text" name="login" id="login" value="" placeholder="Identifiant" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label for="loginPass" class="col-xs-4">Mot de passe</label>
            <div class="col-xs-6">
                <input type="password" name="pass" id="pass" value="" placeholder="Mot de passe" class="form-control">
            </div>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%;">
            <i class="glyphicon glyphicon-log-in"></i> Connexion
        </button>
        <?= form_close(); ?>
    </div>
</div>
<br>
<div class="row" style="margin-top:10px;">
    <div class="col-xs-12 col-sm-4 col-sm-offset-4 alert alert-info" style="text-align: center;">
        <a href="http://archives.carreauximportnegoce.fr">
            Archives 2014-2017
        </a>
    </div>
</div>