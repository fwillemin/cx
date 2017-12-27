$(document).ready(function () {
    
    $.post(chemin + 'facturation/getEncaissementChart', {}, function (retour) {

//        var ctx = document.getElementById("graphExport");
        var myChart = new Chart("graphEncaissements", {
            type: 'line',
            data: {
                labels: retour.labels,
                datasets: [{
                        label: 'CA journalier',
                        data: retour.data,
                        backgroundColor: '#cccccc',                        
                        borderColor: '#ea1936',
                        lineTension: 0.25
                    }]
            },
            options: {
                
                scales: {
                    yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                }
            }
        });

    }, 'json')

    /* -- Facturation --*/
    $('.blNonFacturesClientSelect').on('click', function () {
        $('.blAFacturer, .btnAddFacture').hide();
        $('.' + $(this).attr('id')).show();
    });

    $('.btnAddFacture').on('click', function (e) {
        e.stopPropagation();
    });
    $('#extractStart, #extractEnd').on('change', function () {
        $.post(chemin + 'facturation/addPeriode', { start: $('#extractStart').val(), end: $('#extractEnd').val() }, function (data) {
            if (data.type == 'success')
                window.location.reload();
        }, 'json');
    });

    /* -- Caisse -- */
    $('#addCaisseMontant').on('change', function () {
        $(this).val(parseFloat($(this).val().replace(",", ".")));
    });
    $('#formAddMouvementCaisse').on('submit', function (e) {
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'facturation/addMouvementCaisse', donnees, function (data) {
            $('#formAddMouvementCaisse .alert').remove();
            if (data.type == 'success') {
                window.location.reload();
            } else {
                $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + data.message});
            }
        }, 'json');
    });
    
    $('#btnPrintCaisse').on('click', function () {
        $('#impression').print();
    });
    
    function caisseRAZ() {
        $('#addCaisseId').val('');
        $('#addCaisseDetail').val('');
        $('#addCaisseMontant').val('');
        $('#btnSubmitAddMouvementCaisse').text('Ajouter');
        $('#titreActionCaisse').text('Ajouter un mouvement de caisse');
        $('#btnAbortAddMouvementCaisse').hide();
        $('#btnDelCaisse').hide();
    }
    
    $('.btnModCaisse').on('click', function () {
        $.post(chemin + 'facturation/getMouvementCaisse', {caisseId: $(this).attr('cible')}, function (data) {
            if (data.type == 'success') {
                $('#addCaisseId').val(data.caisse.caisseId);
                $('#addCaisseDate').val(refactor_date(data.caisse.caisseDate));
                $('#addCaisseDetail').val(data.caisse.caisseDetail);
                $('#addCaisseMontant').val(data.caisse.caisseMontant);
                $('#addCaisseType').val(data.caisse.caisseType);
                $('#btnSubmitAddMouvementCaisse').text('Modifier');
                $('#titreActionCaisse').text('Modifier un mouvement de caisse');
                $('#btnAbortAddMouvementCaisse').show();                
                $('#btnDelCaisse').show();
            }
        }, 'json');
    });
    
    $('#btnAbortAddMouvementCaisse').on('click', function () {
        caisseRAZ();
    });
    
    $('#btnDelCaisse').on('dblclick', function () {
        $.post(chemin + 'facturation/delMouvementCaisse', {caisseId: $('#addCaisseId').val()}, function (data) {
            switch(data.type) {
                case 'success':
                    window.location.reload();
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + data.message});
                    break;
            }
        }, 'json');
    });
    
    /* -- Remise de cheques -- */
    $('.addRemiseCheque').on('change', function () {
        $('#listeChequeARemettre tr').remove();
        var totalRemise = 0;
        $('.addRemiseCheque').each(function () {
            if ($(this).prop('checked')) {
                totalRemise += parseFloat($(this).closest('tr').children('td').eq(3).text());
                $('#listeChequeARemettre').append('<tr><td>' + $(this).closest('tr').children('td').eq(1).text() + '</td><td style="text-align:left;">' + $(this).closest('tr').children('td').eq(2).text() + '</td><td style="text-align: right;">' + $(this).closest('tr').children('td').eq(3).text() + '</td></tr>');
            }
        }).promise().done(
                function () {
                    $('#totalRemise').text(Math.round(totalRemise * 100) / 100);
                }
            )
    });
    
    $('#formAddRemiseCheque').on('submit', function (e) {
        e.preventDefault();
        var totalRemise = 0;
        var remiseReglements = [];
        $('.addRemiseCheque').each(function () {
            if ($(this).prop('checked')) {
                console.log($(this).closest('tr').children('td').eq(3).text());
                totalRemise += parseFloat($(this).closest('tr').children('td').eq(3).text());
                remiseReglements.push($(this).val());
            }
        }).promise().done(
                function () {
                    $.post(chemin + 'facturation/addRemiseCheque', {date: $('#addRemiseDate').val(), banque: $('#addRemiseBanque').val(), reglements: remiseReglements, total: totalRemise}, function (data) {
                        switch(data.type) {
                            case 'success':
                                window.location.reload();
                                break;
                            case 'error':
                                $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + data.message});
                                break;
                        }
                    }, 'json');
                }
            )
    });
        
    $('.btnViewRemise').on('click', function () {
        var elem = $(this);
        $.post(chemin + 'facturation/getRemise', { remiseId: elem.attr('cible') }, function (data) {
            switch(data.type) {
                case 'success':
                    $('#tableViewRemise tr').remove();
                    $('#modalRemise .modal-header h4').text('Remise de chèques N°' + data.remise.remiseId + ' du ' + refactor_date(data.remise.remiseDate, 'human') + ' - ' + data.remise.remiseBanque );
                    for (i = 0; i < data.reglements.length; i++) {
                        $('#tableViewRemise').append('<tr><td>' + data.reglements[i].reglementClient + '</td><td>Facture ' + data.reglements[i].reglementFactureId + '</td><td style="text-align:right;">' + data.reglements[i].reglementTotal + '€</td></tr>');
                    }
                    $('#viewRemiseTotal').text( 'Total de la remise de chèque : ' + data.remise.remiseTotal + '€');
                    $('#btnDelRemise').attr('cible', data.remise.remiseId);
                    $('#modalRemise').modal('show');
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + data.message});
                    break;
            }
        }, 'json');
    });
        
    $('#btnDelRemise').on('dblclick', function () {
        $.post(chemin + 'facturation/delRemise', {remiseId: $(this).attr('cible')}, function (data) {
            if (data.type == 'success') {
                window.location.reload();
            }
        }, 'json');
    });
    /* -- Factures non payees -- */
    $('.btnDelFactureAsk').on('click', function(){
        $('#btnDelFacture').attr('cible', $(this).attr('cible') );
        $('#modalDelFacture').modal('show');
    });
    
    $('#btnDelFacture').on('click', function () {
        $.post(chemin + 'factures/delFacture', {factureId: $(this).attr('cible')}, function (data) {
            if (data.type == 'success') {
                window.location.reload();
            }
        }, 'json');
    });
    $('#rechFacture').on('change', function () {
        window.location.assign(chemin + 'facturation/facturesNonPayees/' + parseInt($(this).val()));
    });


});

