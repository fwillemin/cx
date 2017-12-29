$(document).ready(function () {

    $('.modAvoirQte').on('change', function(){
        if( parseFloat( $(this).val()) < 0 ){
            $(this).val( 0 );
            $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + 'La quantité doit être supérieure à zero'});
        } else if(parseFloat( $(this).val()) > $(this).attr('data-maxi')) {
            $(this).val( $(this).attr('data-maxi'));
            $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + 'La quantité doit être inférieure à la quantité facturée'});
        }
        
        /* Mise à jour de la quantité avec le multiple */        
        var valideQte = majMultiple($(this).val(), $(this).attr('data-multiple'));
        console.log(valideQte);
        $(this).val( valideQte );
        
        $.post(chemin + 'factures/modAvoirQte', {rowId: $(this).closest('tr').attr('data-rowid'), qte: $(this).val()}, function(retour){
            switch (retour.type) {
                case 'success':
                    window.location.reload();
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
            }
        }, 'json');
        
    });    
    
    $('.modAvoirName').on('change', function(){
        $.post(chemin + 'factures/modAvoirName', {rowId: $(this).closest('tr').attr('data-rowid'), name: $(this).val()}, function(retour){
            switch (retour.type) {
                case 'success':
                    window.location.reload();
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
            }
        }, 'json');
    });
    
    $('.modAvoirPrix').on('change', function(){        
        $.post(chemin + 'factures/modAvoirPrix', {rowId: $(this).closest('tr').attr('data-rowid'), prix: $(this).val()}, function(retour){
            switch (retour.type) {
                case 'success':
                    window.location.reload();
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
            }
        }, 'json');
    });   
    
    $('#btnAvoirEnregistrer').on('click', function(){
        $.post(chemin + 'factures/addAvoir', {}, function(retour){
            switch (retour.type) {
                case 'success':
                    window.location.assign(chemin + 'factures/ficheFacture/' + retour.factureId);
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
            }
        }, 'json');
    });  
    
   
    /* -- Reglements -- */
    $('#formAddReglement').on('submit', function (e) {
        e.preventDefault();
        $('#btnAddReglementSubmit').hide();
        $('#loaderReglement').show();
        var donnees = $(this).serialize();
        $.post(chemin + 'factures/addReglement', donnees, function (data) {
            switch (data.type) {
                case 'success':
                    window.location.reload();
                    break;
                case 'error':
                    $('#btnAddReglementSubmit').show();
                    $('#loaderReglement').hide();
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + data.message});
                    break;
            }
        }, 'json');
    });
    
    $('#addReglementObjet').on('change', function () {
        if( $(this).val() == '0' ){
            $('#addReglementMontant').val('');
        } else {
            $.post(chemin + 'factures/resteAPayer', {factureId: $(this).val()}, function (data) {
                switch(data.type){
                    case 'success':
                        $('#addReglementMontant').val(data.RAP);
                        break;
                    case 'error':
                        $('#addReglementMontant').val('');
                        $.toaster({priority: 'danger', title: '<strong><i class="fas fa-warning"></i> Oups</strong>', message: '<br>' + data.message});
                        break;
                }
            }, 'json');
        }
    });    
    
    function reglementRAZ() {
        $('#addReglementId').val('');
        $('#addReglementMontant').val('');
        $('#addReglementMotif').val('');
        $('#addReglementMotif').hide();
        //$('#addReglementObjet').show();
        $('#btnAddReglementSubmit').attr('class', 'btn btn-sm btn-primary');
        $('#btnAddReglementSubmit').html('<i class="glyphicon glyphicon-piggy-bank"></i> Payer');
        $('#btnAddReglementCancel').hide();
    }
    
    $('#btnAddReglementCancel').on('click', function(){reglementRAZ()});
    
    $('.btnModReglement').on('click', function(){
        console.log('test');
        reglementRAZ();
        $('#addReglementMotif').show();        
        $('#btnAddReglementCancel').show();
        $('#btnAddReglementSubmit').html('<i class="fas fa-pencil-alt"></i> Modifier');
        $('#btnAddReglementSubmit').attr('class', 'btn btn-sm btn-danger');
        $('#addReglementId').val( $(this).closest('tr').attr('data-reglementid'));
        $('#addReglementMontant').val( $(this).closest('tr').attr('data-reglementMontant') );
        $('#addReglementMode option[value="' + $(this).closest('tr').attr('data-reglementmodeid') + '"]').prop('selected', true);
        $('#addReglementObjet option[value="' + $(this).closest('tr').children('td').eq('3').find('select').val() + '"]').prop('selected', true);    
    });

    $('.btnSendAvoirEmail').on('dblclick', function () {
        $.post(chemin + 'factures/sendAvoirByEmail/', {avoirId: $(this).closest('tr').attr('data-avoirid')}, function (retour) {
            if (retour.type == 'success') {
                $.toaster({priority: 'success', title: '<strong><i class="far fa-hand-peace"></i> OK</strong>', message: '<br>' + 'Avoir envoyé'});
            } else {
                $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
            }
        }, 'json');
    });
    
    $('#sendFactureByEmail').on('dblclick', function () {
        $.post(chemin + 'factures/sendFactureByEmail/', {factureId: $(this).attr('data-factureid')}, function (retour) {
            if (retour.type == 'success') {
                $.toaster({priority: 'success', title: '<strong><i class="far fa-hand-peace"></i> OK</strong>', message: '<br>' + 'Facture envoyée'});
            } else {
                $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
            }
        }, 'json');
    });
});

