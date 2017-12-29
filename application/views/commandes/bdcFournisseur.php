<table>
    <tr style="font-size:10px;">
        <td>
            <img src="<?php echo base_url('assets/img/cinSmall.png'); ?>" style="widtd:200px;"/><br/>
            SARL AUX CARREAUX DE MAX<br/>
            21, Av Maréchal Leclerc de Hautecloque<br/>
            59360 LE CATEAU CAMBRESIS<br/>
            Téléphone : 03.27.77.42.46<br/>
            Email :<br/>
            Commercial : carreaux-import-negoce@orange.fr<br/>
            Direction : ledieumaxime59@orange.fr
        </td>
        <td style="text-align: right;">
            Le <?php echo date('d/m/Y', $commande->commandeDate); ?>
            <h1><?php echo 'Commande N°' . $commande->commandeId; ?></h1>
            <br/><br/>
            <p style="font-size:13px;">
                Usine / Fournisseur : <?php echo $commande->usine; ?><br/>
                Code client : <?php echo $commande->usineCodeClient; ?><br/>
                <?php echo $commande->usineEmail; ?>
            </p>
        </td>
    </tr>
</table>
<br/>
<br/>
<br/>
<table class="table table-bordered" cellspacing="1" style="font-size:10px;">
    <tr style="background-color:#efeef5; font-weight:bold;">
        <td style="width: 60px; text-align: center; font-size:10px;">N° Appro</td>
        <td style="font-size:10px; width:360px;">Designation</td>
        <td style="text-align: right; font-size:10px; width:80px;">Qte</td>
        <td style="text-align: left; font-size:10px; width:40px;">Unité</td>
    </tr>
    <tbody>
        <?php
        if ($appros):
            foreach ($appros as $a):
                ?>
                <tr>
                    <td style="text-align: center; font-size:10px;">
        <?php echo $a->approId; ?>
                    </td>
                    <td style=" font-size:10px; width:360px;">
        <?php echo $a->produitDesignation; ?>
                    </td>
                    <td style="text-align: right; font-size:10px; width:80px;">
        <?php echo $a->approQte; ?>
                    </td>
                    <td style="text-align: left; font-size:10px; width:40px;">
        <?php echo $this->cxwork->affUnite($a->produitUniteId); ?>
                    </td>
                </tr>
                <?php
            endforeach;
        endif;
        ?>
    </tbody>
</table>
<br/>
<br/>
<br/>
<br/>
<p>
    Merci de nous confirmer la bonne récéption de cette commande.
    Cordialement.
</p>
