$(document).ready(function () {

    $('#tableProduits').bootstrapTable({
        idField: 'produitId',
        url: chemin + 'produits/getAllProduits',
        pagination: true,
        search: true,
        cardView: false,
        showColumns: true,
        pageSize: 50,
        contextMenu: '#context-menu',
        onClickRow: function(row){ window.location.assign( chemin + 'produits/ficheProduit/' + row.produitId)},
        //onContextMenuItem: contextActions,
        columns: [[{
                    field: 'produitId',
                    title: 'ID',
                    width: 30,
                    align: 'center',
                    visible: false
                }, {
                    field: 'produitDesignation',
                    title: 'Designation',
                    width: 340,
                    align: 'left'
                }, {
                    field: 'usine',
                    title: 'Usine',
                    sortable: true,
                    width: 80
                }, {
                    field: 'famille',
                    title: 'Famille',
                    width: 150
                }, {
                    field: 'produitMultiple',
                    title: 'Cond.',
                    width: 80,
                    align: 'right'
                }, {
                    field: 'produitUniteId',
                    title: 'Unité',
                    formatter: uniteFormatter,
                    width: 20,
                    align: 'center'
                }, {
                    field: 'produitStock',
                    title: 'Stock',
                    sortable: true,
                    width: 80,
                    formatter: stockFormatter,
                    align: 'right'
                }
            ]
        ]
    });

    /* Formatage des cellules de la table */
    function uniteFormatter(value) {
        switch (value) {
            case '1':
                return 'pièce';
                break;
            case '2':
                return 'm²';
                break;
            case '3':
                return 'mètre';
                break;
        }
    }
    function stockFormatter(value) {
        if (parseFloat(value) > 0) {
            return '<span style="color:green;">' + Math.round(value * 100) / 100 + '</span>';
        } else {
            return '';
        }
    }

    /* Actions du menu contextuel sur les articles */
    function contextActions(row, $el) {
        switch ($el.data("item")) {

            case 'fiche':
                /* Accès à la fiche produit */
                window.location.assign(chemin + 'produits/ficheProduit/' + row.produitId);
                break;
        }
    }

    /* ----------------------------------- */

    function produitRAZ() {
        $('#addProduitId').val('');
        $('#addProduitRefUsine').val('');
        $('#addProduitDesignation').val('');
        $('#addProduitStock').prop('checked', false);
        $('#addProduitBain').prop('checked', false);
        $('#addProduitMultiple').val('');
        $('#addProduitAchatUnitaire').val('');
        $('#addProduitSeuilPalette').val('');
        $('#addProduitAchatPalette').val('');
        $('#addProduitVenteUnitaire').val('');
        $('#saisieProduitTTC').val('');
        $('#addProduitPoids').val('');
        $('#addProduitEAN').val('');
        $('#addProduitTVA').val('20');
        $('#addProduitTVA').closest('.form-group').attr('class', 'form-group has-success');
    }

    $('#saisieProduitTTC').on('change', function () {
        $(this).val(parseFloat($(this).val()));
        $('#addProduitVenteUnitaire').val(Math.round(($(this).val() / 1.2) * 10000) / 10000);
    });
    $('#addProduitVenteUnitaire').on('change', function () {
        $(this).val(parseFloat($(this).val()));
        $('#saisieProduitTTC').val(Math.round(($(this).val() * 1.2) * 10000) / 10000);
    });

    $('#btnAddProduit').on('click', function () {
        produitRAZ();
        $('#modalAddProduit h4').text('Ajouter un produit');
        $('#btnAddProduitSubmit').text('Ajouter');
        $('#modalAddProduit').modal('show');
    });

    $('#btnModProduit').on('click', function () {

//        $.post(chemin + 'produits/getProduit', {produitId: $(this).attr('cible')}, function (data) {
//            if (data.type == 'success') {
//                $('#addProduitId').val(data.produit.produitId);
//                $('#addProduitType option[value="' + data.produit.produitType + '"]').prop('selected', true);
//                $('#addProduitCodeComptable').val(data.produit.produitCodeComptable);
//                $('#addProduitNom').val(data.produit.produitNom);
//                $('#addProduitRaisonSociale').val(data.produit.produitRaisonSociale);
//                $('#addProduitPrenom').val(data.produit.produitPrenom);
//                $('#addProduitAdresse1').val(data.produit.produitAdresse1);
//                $('#addProduitAdresse2').val(data.produit.produitAdresse2);
//                $('#addProduitCp').val(data.produit.produitCp);
//                $('#addProduitVille').val(data.produit.produitVille);
//                $('#addProduitPays').val(data.produit.produitPays);
//                $('#addProduitTel').val(data.produit.produitTel);
//                $('#addProduitPortable').val(data.produit.produitPortable);
//                $('#addProduitFax').val(data.produit.produitFax);
//                $('#addProduitEmail').val(data.produit.produitEmail);
//                $('#addProduitIntracom').val(data.produit.produitIntracom);
//                $('#addProduitModeReglement option[value="' + data.produit.produitModeReglementId + '"]').prop('selected', true);
//                $('#addProduitConditionReglement option[value="' + data.produit.produitConditionReglementId + '"]').prop('selected', true);
//
//            }
//        }, 'json');
        $('#saisieProduitTTC').val(Math.round(($('#addProduitVenteUnitaire').val() * 1.2) * 10000) / 10000);
        $('.has-error').attr('class', 'form-group has-success');
        $('#modalAddProduit h4').text('Modfier le produit ');
        $('#btnAddProduitSubmit').text('Modifier');
        $('#modalAddProduit').modal('show');
    });

    $('#btnDelProduit').on('click', function () {
        $('#confirmDelArticle').show();
    });

    $('#btnDelProduitAvort').on('click', function () {
        $('#confirmDelArticle').hide();
    });

    $('#btnDelProduitConfirm').on('click', function () {
        var produit = $(this).attr('data-produitid');
        $.post(chemin + 'produits/delProduit', {produitId: produit}, function (data) {
            if (data.type == 'success') {
                window.location.assign(chemin + 'produits');
            }
        }, 'json');
    });

    $('.requiredField').on('change', function () {
        var elem = $(this).closest('.form-group');
        if ($(this).val() != '') {
            if ($(this).attr('id') == 'addProduitCp' && $('#addProduitVille').val() == null) {
                elem.attr('class', 'form-group has-error');
            } else {
                elem.attr('class', 'form-group has-success');
            }
        } else {
            elem.attr('class', 'form-group has-error');
        }
    });

    $('#formAddProduit').on('submit', function (e) {
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'produits/addProduit', donnees, function (retour) {
            switch (retour.type) {
                case 'success':
                    window.location.assign( chemin + 'produits/ficheProduit/' + retour.produitId );
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
            }
        }, 'json');
    });

    $('#btnCopyProduit').on('click', function () {
        $.post(chemin + 'produits/copyProduit', {produitId: $(this).closest('div').attr('data-produitid')}, function (retour) {
            switch (retour.type) {
                case 'success':
                    window.location.assign(chemin + 'produits/ficheProduit/' + retour.produitId);
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
            }
        }, 'json');
    });

    $('#formAddStock').on('submit', function (e) {
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'produits/addStock', donnees, function (retour) {
            switch (retour.type) {
                case 'success':
                    window.location.reload();
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
            }
        }, 'json');
    });

    /* Corrige la quantité d'ajout en stock ou en commande par le multiple du produit */
    $('#addStockQte, #addApproQte').on('change', function () {
        if (parseFloat($(this).val()) > 0) {
            var qteMultiple = Math.ceil(parseFloat($(this).val()) / parseFloat($('#produitMultiple').text()));
        } else {
            var qteMultiple = Math.floor(parseFloat($(this).val()) / parseFloat($('#produitMultiple').text()));
        }
        $(this).val(qteMultiple * parseFloat($('#produitMultiple').text()));
    });

    $('.ligneStock').on('click', function () {
        $('#addStockBain').val($(this).children('td').eq(1).text());
        $('#addStockCalibre').val($(this).children('td').eq(2).text());
        $('#addStockPrixAchat').val(parseFloat($(this).children('td').eq(3).text()));
    });

    $('#formAddAppro').on('submit', function (e) {
        e.preventDefault();
        /* on passe la quantité en verif de multiple */
        $('#addApproQte').val(Math.round(Math.ceil(Math.round($('#addApproQte').val() / $('#addApproMultiple').val() * 100) / 100) * $('#addApproMultiple').val() * 100) / 100);
        var donnees = $(this).serialize();
        $.post(chemin + 'commandes/addApproDirect', donnees, function (data) {
            $('.alert').remove();
            if (data.type == 'success') {
                window.location.reload();
            } else {
                $('#formAddAppro').after('<div class="alert alert-danger">' + data.message + '</div>');
            }
        }, 'json');
    });

    /* -- Famille -- */
    $('#addFamille').on('change', function () {
        upperConvert($(this))
    });
    $('#btnAddFamille').on('click', function () {
        $('#addFamilleId').val('');
        $('#addFamilleNom').val('');
        $('#btnDelFamille').attr('data-familleId', '');
        $('#btnDelFamille').css('display', 'none');
        $('.has-success').attr('class', 'form-group has-error');
        $('#modalAddFamille h4').text('Ajouter une famille');
        $('#btnAddFamilleSubmit').text('Ajouter');
        $('#modalAddFamille').modal('show');
    });

    $('.btnModFamille').on('click', function () {
        $.post(chemin + 'produits/getFamille', {familleId: $(this).attr('data-familleid')}, function (data) {
            if (data.type == 'success') {
                $('#addFamilleId').val(data.famille.familleId);
                $('#addFamilleNom').val(data.famille.familleNom);
                $('#btnDelFamille').attr('data-familleid', data.famille.familleId);
                $('#btnDelFamille').css('display', 'block');
                $('.has-error').attr('class', 'form-group has-success');
                $('#modalAddFamille h4').text('Modifier la famille ' + data.famille.famille);
                $('#btnAddFamilleSubmit').text('Modifier');
                $('#modalAddFamille').modal('show');
            }
        }, 'json');
    });

    $('#formAddFamille').on('submit', function (e) {
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'produits/addFamille', donnees, function (retour) {
            switch (retour.type) {
                case 'success':
                    window.location.reload();
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
            }
        }, 'json');
    });

    $('#btnDelFamille').on('dblclick', function (e) {        
        $.post(chemin + 'produits/delFamille', {familleId: $(this).attr('data-familleid')}, function (retour) {
            switch (retour.type) {
                case 'success':
                    window.location.reload();
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
            }
        }, 'json');
    });
    
    /* Usines */
    
    $('#addUsine').on('change',function(){upperConvert($(this))});
    
    $('#btnAddUsine').on('click',function(){
        $('#addUsineId').val('');
        $('#addUsineNom').val('');
        $('#addUsineEmail').val('');
        $('#addUsineCodeClient').val('');
        $('#btnDelUsine').attr('data-usineid','');
        $('#btnDelUsine').css('display','none');
        $('.has-success').attr('class','form-group has-error');
        $('#modalAddUsine h4').text('Ajouter une usine');
        $('#btnAddUsineSubmit').text('Ajouter');
        $('#modalAddUsine').modal('show');
    });
    
    $('.btnModUsine').on('click',function(){
        $.post( chemin + 'produits/getUsine', { usineId: $(this).attr('data-usineid') },function(data){
            if(data.type == 'success'){
                $('#addUsineId').val(data.usine.usineId);
                $('#addUsineNom').val(data.usine.usineNom);
                $('#addUsineEmail').val(data.usine.usineEmail);
                $('#addUsineCodeClient').val(data.usine.usineCodeClient);
                $('#btnDelUsine').attr('data-usineid',data.usine.usineId);
                $('#btnDelUsine').css('display','block');
                $('.has-error').attr('class','form-group has-success');
                $('#modalAddUsine h4').text('Modifier l\'usine ' + data.usine.usineNom);
                $('#btnAddUsineSubmit').text('Modifier');
                $('#modalAddUsine').modal('show');
            }
        },'json');
    });
    
    $('#formAddUsine').on('submit',function(e){
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post( chemin + 'produits/addUsine', donnees, function(retour){
             switch (retour.type) {
                case 'success':
                    window.location.reload();
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
            }
        },'json');
    });
    
    $('#btnDelUsine').on('dblclick',function(e){
        e.preventDefault();        
        $.post( chemin + 'produits/delUsine', {usineId: $(this).attr('data-usineid') },function(retour){
             switch (retour.type) {
                case 'success':
                    window.location.reload();
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
            }
        },'json');
    });

});

