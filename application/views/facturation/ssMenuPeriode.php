<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3" style="padding: 5px; border-bottom: 1px solid black; background-color: orangered;">
            <div class="input-group">
                <span class="input-group-addon">Période du</span>
                <input type="date" name="extractStart" id="extractStart" class="form-control input-sm" value="<?php
                if ($this->session->userdata('extractStart'))
                    echo date('Y-m-d', $this->session->userdata('extractStart'));
                else
                    echo date('Y-m') . '-01';
                ?>" >
                <span class="input-group-addon">au</span>
                <input type="date" name="extractEnd" id="extractEnd" class="form-control input-sm" value="<?php
                if ($this->session->userdata('extractEnd'))
                    echo date('Y-m-d', $this->session->userdata('extractEnd'));
                else
                    echo date('Y-m-t');
                ?>" >
                <span class="input-group-btn">
                    <button class="btn btn-warning btn-sm" id="lancerRecherchePeriode">
                        <i class="fas fa-search"></i> Rechercher
                    </button>
                </span>
            </div>
        </div>
    </div>
</div>