<table>
    <tr style="font-size:10px;">
        <td>
            <img src="<?php echo base_url('assets/img/logoSmall.png'); ?>" style="height:40px;"><br>
            SARL AUX CARREAUX DE MAX<br>
            21, Av Maréchal Leclerc de Hautecloque<br>
            59360 LE CATEAU CAMBRESIS<br>
            Téléphone : 03.27.77.42.46<br>
            <span style="font-weight:bold; color:#2e6da4;">www.carreauximportnegoce.fr</span><br>
            Commercial : carreaux-import-negoce@orange.fr<br>
            Direction : ledieumaxime59@orange.fr
        </td>
        <td style="text-align: right;">
            Le <?php echo date('d/m/Y'); ?>
            <h1>Remises de chèques</h1>
            <span style="font-size:13px; font-weight: bold;">
                Période du <?= date('d/m/Y', $debut) . ' au ' . date('d/m/Y', $fin); ?> 
            </span>          
        </td>
    </tr>        
</table>
<br>

<h2>Remises de chèques du <?php echo date('d/m/Y', $debut) . ' au ' . date('d/m/Y', $fin); ?></h2>
<table style="font-size: 10px;" border="1">
    <tr style="background-color: lightgray;">            
        <td style="width: 50px;"></td>
        <td style="width: 290px;">Client</td>
        <td style="width: 100px;">Facture</td>
        <td style="text-align: right; width: 120px;">Montant</td>                
    </tr>   
    <?php
    if (!empty($remises)):
        foreach ($remises as $r):
            ?>
            <tr  style="background-color: lightgoldenrodyellow; font-size:12px;">
                <td colspan="3">Remise N°<?php echo $r->getRemiseId() . ' du ' . date('d/m/Y', $r->getRemiseDate()) . ' Banque : <strong>' . $r->getRemiseBanque() . '</strong>'; ?></td>
                <td style="text-align: right; border-top:1px solid black;"><?php echo number_format($r->getRemiseTotal(), 2, ',', ' ') . '€'; ?></td>
            </tr>
            <?php
            /* cheques associés */
            foreach ($r->getRemiseReglements() as $reg):
                ?>
                <tr>
                    <td></td>
                    <td>
                        <?php
                        if ($reg->getReglementClient()->getClientType() == 1):
                            echo $reg->getReglementClient()->getClientNom() . ' ' . $reg->getReglementClient()->getClientPrenom();
                        else:
                            echo $reg->getReglementClient()->getClientRaisonSociale();
                        endif;
                        ?>
                    </td>
                    <td><?php echo 'Facture ' . $reg->getReglementFactureId(); ?></td>
                    <td style="text-align: right;">
            <?php echo number_format($reg->getReglementTotal(), 2, ',', ' ') . '€'; ?>
                    </td>
                </tr>
                <?php
            endforeach;

        endforeach;
    endif;
    ?>
</table>
