<div class="modal fade" id="modalSession" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Données de session</h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-condensed table-striped">
                    <?php
                    foreach ($this->session->userdata() as $key => $val):
                        if (is_array($val)):
                            echo '<tr><td>' . $key . '</td><td>' . nl2br(print_r($val, 1)) . '</td></tr>';
                        else:
                            echo '<tr><td>' . $key . '</td><td>' . $val . '</td></tr>';
                        endif;
                    endforeach;
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" media="screen" href="<?php echo base_url('assets/css/ball-scale-pulse.min.css'); ?>" >

<script defer src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
<script defer src="<?php echo base_url('assets/bootstrap-3.3.7/js/bootstrap.min.js'); ?>"></script>
<script defer type="text/javascript" src="<?php echo base_url('assets/bootstrap-table/dist/bootstrap-table.min.js'); ?>"></script>
<script defer type="text/javascript" src="<?php echo base_url('assets/bootstrap-table/dist/bootstrap-table-contextmenu.min.js'); ?>"></script>
<script defer type="text/javascript" src="<?php echo base_url('assets/bootstrap-table/dist/locale/bootstrap-table-fr-FR.min.js'); ?>"></script>

<?php if ($this->ion_auth->logged_in()): ?>
    <script defer type="text/javascript" src="<?php echo base_url('assets/chart/Chart.min.js'); ?>"></script>
    <script defer type="text/javascript" src="<?php echo base_url('assets/MegaNavbar/MegaNavbar.js'); ?>"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.2/js/all.js"></script>
<?php endif; ?>

<script defer src="<?php echo base_url('assets/js/toaster.js'); ?>"></script>
<!--<script defer src="<?php echo base_url('assets/bootstrap/js/bootstrap-select.min.js'); ?>"></script>-->
<script defer src="<?php echo base_url('assets/js/cx.js'); ?>"></script>
<script defer type="text/javascript" src="<?php echo base_url('assets/js/' . $this->uri->segment(1) . '.js'); ?>"></script>

</body>

</html>