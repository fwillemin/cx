$(document).ready(function () {

    $('#tableDevis').bootstrapTable({
        idField: 'devisId',
        url: chemin + 'chiffrages/getAllDevis',
        pagination: true,
        search: true,
        cardView: false,
        showColumns: true,
        pageSize: 50,
        contextMenu: '#context-menu',
        //onContextMenuItem: contextActionsDevis,
        onClickRow: function (row) {
            window.location.assign(chemin + 'chiffrages/reloadDevis/' + row.devisId)
        },
        columns: [[{
                    field: 'devisId',
                    title: 'ID',
                    width: 30,
                    align: 'center',
                    visible: true
                }, {
                    field: 'devisDate',
                    title: 'Date',
                    formatter: dateFormatter,
                    width: 150
                }, {
                    field: 'devisRaisonSociale',
                    title: 'Raison Sociale',
                    width: 340,
                    align: 'left'
                }, {
                    field: 'devisClient',
                    title: 'Client',
                    width: 340,
                    align: 'left'
                }, {
                    field: 'devisVille',
                    title: 'Ville',
                    sortable: true,
                    width: 280
                }, {
                    field: 'devisTotalHT',
                    title: 'Total HT',
                    width: 80,
                    align: 'right'
                }
            ]
        ]
    });

    /* Formatage des cellules de la table */
    function dateFormatter(value) {
        return refactor_date(value, 'human');
    }

    $('#purgeDevis').on('click', function () {
        $('#btnPurgeDevisAvort, #btnPurgeDevisConfirm').show();
        $('#loader').hide();
        $('#confirmPurgeDevis').show();
    });
    $('#btnPurgeDevisAvort').on('click', function () {
        $('#confirmPurgeDevis').hide();
    });
    $('#btnPurgeDevisConfirm').on('dblclick', function () {
        $('#btnPurgeDevisAvort, #btnPurgeDevisConfirm').hide();
        $('#loader').show();
        $.post(chemin + 'chiffrages/purgerDevis', {limitePurge: $('#limitePurge').val()}, function (retour) {
            switch (retour.type) {
                case 'success':
                    $.toaster({priority: 'success', title: '<strong><i class="fas fa-peace"></i> Nettoyé !</strong>', message: '<br>' + retour.nbSupprime + ' devis ont été supprimé.'});
                    $('#confirmPurgeDevis').hide();
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
            }
        }, 'json');
    });

    /* ----- DATE et USER ----- */
    $('#venteDate').on('change', function () {
        $.post(chemin + 'chiffrages/venteDateChange', {venteDate: $(this).val()}, function (data) {

            if (data.type == 'success') {
                window.location.reload();
            } else {
                $('#venteDate').before('<div class="alert alert-danger">Format de date invalide : AAAA-MM-JJ</div>');
            }
        }, 'json');
    });
    $('#venteCollaborateurId').on('change', function () {
        $.post(chemin + 'chiffrages/venteCollaborateurChange', {venteCollaborateurId: $(this).val()}, function (data) {
            if (data.type == 'success') {
                window.location.reload();
            }
        }, 'json');
    });

    /* ----- SELECTION DU CLIENT ----- */
    $('#btnClientSearch').on('click', function () {
        $('#modalClientSearch').modal('show');
    });

    $('#clientSearch').on('keyup', function () {
        $(this).val($(this).val().toUpperCase());
        /* Recherche des clients correspondants */
        $.post(chemin + 'clients/clientSearch', {clientSearch: $(this).val()}, function (data) {

            switch (data.type) {
                case 'success':
                    $('#clientSearchTable .clientSearchLigne').remove();
                    for (var i = 0; i < data.clients.length; i++) {
                        $('#clientSearchTable').append('<tr class="clientSearchLigne" id="' + data.clients[i].clientId + '"><td>' + data.clients[i].clientRaisonSociale + '</td><td>' + data.clients[i].clientNom + ' ' + data.clients[i].clientPrenom + '</td><td>' + data.clients[i].clientVille + '</td></tr>');
                    }
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + data.message});
            }
        }, 'json');
    });

    $('#clientSearchTable').on('click', '.clientSearchLigne', function () {
        $('#venteClientId').val($(this).attr('id'));
        /* recherche des informations pour remplir la div du client selectionné */
        clientSelect($(this).attr('id'));
    });

    function clientSelect(clientId) {
        $.post(chemin + 'chiffrages/venteClientChange', {clientId: clientId}, function (data) {
            switch (data.type) {
                case 'success':
                    window.location.reload();
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + data.message});
                    break;
            }
        }, 'json');
    }

    $('#btnDevisPerdu').confirm({
        title: 'Mais, pourquoi ?!',
        content: 'Merci de choisir la raison pour laquelle le devis a été perdu.',
        type: 'purple',
        closeIcon: true,
        closeIconClass: 'fas fa-times',
        columnClass: 'medium',
        buttons: {
            noProduct: {
                text: 'Offre produit',
                action: function () {
                    devisPerdu($('#btnDevisPerdu').attr('data-devisid'), 2);
                }
            },
            prix: {
                text: 'Prix trop élevé',
                action: function () {
                    devisPerdu($('#btnDevisPerdu').attr('data-devisid'), 3);
                }
            },
            autre: {
                text: 'Autre raison',
                action: function () {
                    devisPerdu($('#btnDevisPerdu').attr('data-devisid'), 4);
                }
            }
        }
    });

    function devisPerdu(devisId, motif) {
        $.post(chemin + 'chiffrages/devisPerdu', {devisId: devisId, motif: motif}, function (data) {
            switch (data.type) {
                case 'success':
                    window.location.assign(chemin + 'chiffrages/reloadDevis/' + devisId);
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + data.message});
                    break;
            }
        }, 'json');
    };

    /* ----- RECHERCHE ARTICLE ----- */
    $('#addArticleDesignation').on('blur', function () {
        $(this).val($(this).val().toUpperCase());
    });
    $('#addArticleDesignation').on('keyup', function () {
        if ($(this).val() === '') {
            $('#addArticlePrixUnitaire').val('');
            $('#saisieTTC').val('');
            $('#addArticleMultiple').val('');
            $('#addArticlePrixUnitaire').prop('disabled', false);
            $('#addArticleUniteId option').prop('disabled', false);
            majArticle();
        } else {
            /* Recherche des produits correspondants */
            $.post(chemin + 'produits/produitSearch', {produitSearch: $(this).val()}, function (data) {
                $('#produitSearchTable .produitSearchLigne').remove();
                if (data.type == 'success') {
                    for (var i = 0; i < data.produits.length; i++) {

                        switch (data.produits[i].produitUniteId) {
                            case '1':
                                unite = 'pièce';
                                break;
                            case '2':
                                unite = 'm²';
                                break;
                            case '3':
                                unite = 'mètre';
                                break;
                        }

                        if (data.produits[i].produitDispo > 0) {
                            color = 'green';
                            stock = data.produits[i].produitDispo + ' ' + unite;
                        } else {
                            color = 'red';
                            stock = '-';
                        }
                        var unite = '';

                        if (data.produits[i].produitGestionStock > 0) {
                            dispo = '<i class="glyphicon glyphicon-stop"></i> ' + data.produits[i].disponibilite;
                        } else {
                            dispo = '';
                        }
                        $('#produitSearchTable').prepend('<tr class="produitSearchLigne" id="' + data.produits[i].produitId + '"><td>' + data.produits[i].produitDesignation + '</td><td>' + data.produits[i].produitUsine + '</td><td>' + data.produits[i].produitPrixVente + '€</td><td>' + data.produits[i].produitMultiple + ' ' + unite + '</td>' +
                                '<td style="color:' + color + ';" class="btn btn-link affModalPopStock">' + stock + '</td></tr>');
                    }
                }
            }, 'json');
        }
    });

    $(this).on('click', '.produitSearchLigne', function () {

        $('#venteArticleId').val($(this).attr('id'));
        /* recherche des informations pour remplir la ligne article */
        $.post(chemin + 'produits/getProduit', {produitId: $(this).attr('id')}, function (data) {
            console.log('ok !!!');
            switch (data.type) {
                case 'success':
                    var tva = 100 + parseInt(data.produit.produitTVA);
                    $('#addArticleProduitId').val(data.produit.produitId);
                    $('#addArticleDesignation').val(data.produit.produitDesignation);
                    $('#addArticlePrixUnitaire').val(data.produit.produitPrixVente);
                    $('#saisieTTC').val(Math.round(data.produit.produitPrixVente * tva) / 100);
                    $('#addArticleMultiple').val(data.produit.produitMultiple);
                    $('#addArticleUniteId option').prop('disabled', true);
                    $('#addArticleUniteId option[value="' + data.produit.produitUniteId + '"]').prop('disabled', false);
                    $('#addArticleUniteId option[value="' + data.produit.produitUniteId + '"]').prop('selected', true);
                    $('#addArticleTauxTVA option[value="' + data.produit.produitTVA + '"]').prop('selected', true);
                    majArticle();
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + data.message});
                    break;
            }
        }, 'json');
    });

    /* ----- OPTIONS ----- */
    $('#addArticleQte').on('change', function () {
        majArticle();
    });

    $('#addArticlePrixUnitaire, #addArticleTauxTVA').on('change', function () {
        var tva = (100 + parseFloat($('#addArticleTauxTVA').val())) / 100;
        $('#saisieTTC').val(Math.round(($('#addArticlePrixUnitaire').val() * tva) * 100) / 100);
        majArticle();
    });

    $('#addArticleRemise').on('change', function () {
        $(this).val(parseInt($(this).val()));
        majArticle();
    });

    $('#saisieTTC').on('change', function () {
        var tva = (100 + parseFloat($('#addArticleTauxTVA').val())) / 100;
        $(this).val(parseFloat($(this).val()));
        $('#addArticlePrixUnitaire').val(Math.round(($(this).val() / tva) * 100) / 100);
        majArticle();
    });

    function majArticle() {
        if ($('#addArticleMultiple').val() !== '' && $('#addArticleMultiple').val() > 0) {
            var valideQte = majMultiple($('#addArticleQte').val(), $('#addArticleMultiple').val());
            $('#addArticleQte').val(valideQte);
        }
        $('#addArticleTotal').val(Math.round($('#addArticleQte').val() * $('#addArticlePrixUnitaire').val() * ((100 - $('#addArticleRemise').val()) / 100) * 100) / 100);
    }

    /* ----- AJOUT ARTICLE AU PANIER ----- */
    $('#formAddArticle').on('submit', function (e) {
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'chiffrages/addCartArticle', donnees, function (data) {
            switch (data.type) {
                case 'success':
                    window.location.reload();
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + data.message});
                    break;
            }
        }, 'json');
    });

    /* -- MODIF LIGNE -- */

    $('#btnAddArticleReset').on('click', function (e) {
        e.preventDefault();
        formAddArticleReset();
    });
    $('.btnArticleDelete').on('click', function (e) {
        e.stopPropagation();
    });
    $('.btnArticleDelete').on('dblclick', function () {
        var rowid = $(this).closest('tr').attr('id');
        $.post(chemin + 'chiffrages/delCartArticle', {rowid: rowid}, function (data) {
            if (data.type == 'success') {
                window.location.reload();
            } else {
                alert(data.message);
            }
        }, 'json');
    });

    function formAddArticleReset() {
        $('.ligneVente').css('background-color', '#FFF');
        $('#btnAddArticleSubmit').text('Ajouter');
        $('#btnAddArticleReset').css('display', 'none');
        $('#addArticleRowid').val('');
        $('#addArticleProduitId').val('');
        $('#addArticleMultiple').val('');
        $('#addArticleDesignation').val('');
        $('#addArticlePrixUnitaire').val('');
        $('#saisieTTC').val('');
        $('#addArticleRemise').val('0');
        $('#addArticleQte').val('');
        majArticle();
    }

    $('.ligneVente').on('click', function () {
        var rowid = $(this).attr('id');
        $.post(chemin + 'chiffrages/getCartArticle', {rowid: rowid}, function (data) {
            $('.ligneVente').css('background-color', '#FFF');
            switch (data.type) {
                case 'success':

                    var prixTTC = Math.round(parseFloat(data.cart.options.prixUnitaire) * (100 + parseFloat(data.cart.options.tauxTVA))) / 100;

                    $('#' + rowid).css('background-color', 'gold');
                    $('#btnAddArticleSubmit').text('Modifier');
                    $('#btnAddArticleReset').css('display', 'block');
                    $('#addArticleRowid').val(data.cart.rowid);
                    $('#addArticleId').val(data.cart.options.articleId);
                    $('#addArticleProduitId').val(data.cart.id);
                    $('#addArticleMultiple').val(data.cart.multiple);
                    $('#addArticleTauxTVA option[value="' + data.cart.options.tauxTVA + '"]').prop('selected', true);
                    $('#addArticleDesignation').val(data.cart.name);
                    $('#addArticlePrixUnitaire').val(data.cart.options.prixUnitaire);
                    $('#saisieTTC').val(prixTTC);
                    $('#addArticleRemise').val(data.cart.remise);
                    $('#addArticleQte').val(data.cart.qty);
                    if (data.cart.id.substring(0, 6) == 'Unique') {
                        $('#addArticleUniteId option').prop('disabled', false);
                    } else {
                        $('#addArticleUniteId option').prop('disabled', true);
                        $('#addArticleUniteId option[value="' + data.cart.options.uniteId + '"]').prop('disabled', false);
                    }
                    $('#addArticleUniteId option[value="' + data.cart.options.uniteId + '"]').prop('selected', true);
                    majArticle();
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + 'Impossible de récupérer les informations de cet ligne...prototype'});
                    break;
            }
        }, 'json');
    });

    $('#venteAcompte, #venteCommentaire').on('change', function () {
        var elem = $(this);
        if ($(this).attr('id') == 'venteAcompte') {
            option = 'Acompte';
        } else {
            option = 'Commentaire';
        }
        $.post(chemin + 'chiffrages/venteChange', {venteOption: option, venteValeur: $(this).val()}, function (data) {

            if (data.type == 'error') {
                $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + data.message});
                elem.before('<div class="alert alert-danger">' + data.message + '</div>');
            }
        }, 'json');
    });

    $('#rechClientDevis').on('keyup', function () {
        $(this).val($(this).val().toUpperCase());
        $('.devisLigne').each(function () {
            if ($(this).children('td').eq(1).text().indexOf($('#rechClientDevis').val()) >= 0) {
                $(this).fadeIn();
            } else {
                $(this).fadeOut();
            }
        });
    });
    $('#rechDevisId').on('keyup', function () {
        $(this).val($(this).val().toUpperCase());
        $('.devisLigne').each(function () {
            if ($(this).children('td').eq(0).text().indexOf($('#rechDevisId').val()) >= 0) {
                $(this).fadeIn();
            } else {
                $(this).fadeOut();
            }
        });
    });
    $('#btnDevisReset').on('dblclick', function () {
        window.location.assign(chemin + 'chiffrages/resetDevisEncours');
    });
    $('#btnDevisEnregistrer').on('click', function () {
        $.post(chemin + 'chiffrages/addDevis', {}, function (data) {

            if (data.type == 'success') {
                window.location.reload();
            } else {
                $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + data.message});
            }
        }, 'json');
    });

    /* -- Livraison -- */
//    $('#btnModalBl').on('click', function () {
//        $('#modalLivraison').modal('show');
//    });
//    $('#modalLivraison').on('hidden.bs.modal', function () {
//        window.location.assign(chemin + 'chiffrages/reloadBdc/' + $('#idVenteLivree').val());
//    });
//    $('#btnAddBl').on('dblclick', function () {
//        var livraison = [];
//        $('.articlesALivrer').each(function () {
//            article = [];
//            if ($(this).children('td').eq(3).find('.qteALivrer').val() != '0') {
//                article.push($(this).attr('id'));
//                article.push($(this).children('td').eq(3).find('.qteALivrer').val());
//                article.push($(this).children('td').eq(4).find('.stockADeduire').val());
//                livraison.push(article);
//            }
//        }).promise().done(
//                function () {
//                    $.post(chemin + 'livraisons/addBl', {livraisons: livraison, bdcId: $('#idVenteLivree').val()}, function (data) {
//                        $('#modalLivraison .alert').remove();
//                        if (data.type == 'success') {
//                            $('#btnAddBl').fadeOut();
//                            $('#livraisonTable').before('<div class="alert alert-success">Un Bl est enregistré pour ce bon de commande.</div>');
//                            $('#livraisonTable').remove();
//                        } else {
//                            $('#modalLivraison .modal-footer').prepend('<div class="alert alert-danger">' + data.message + '</div>');
//                        }
//
//                    }, 'json');
//                }
//        );
//    });
//
//    $('.delBl').on('dblclick', function () {
//        var elem = $(this);
//        $.post(chemin + 'livraisons/delBl', {blId: elem.attr('cible')}, function (data) {
//            if (data.type == 'success') {
//                window.location.assign(chemin + 'chiffrages/reloadBdc/' + elem.attr('bdc'));
//            }
//        }, 'json');
//    });

    /* -- Facture --*/
//
//    $('#formAddReglement').on('submit', function (e) {
//        e.preventDefault();
//        $('#btnAddReglementSubmit').hide();
//        $('#loaderReglement').show();
//        var donnees = $(this).serialize();
//        $.post(chemin + 'factures/addReglement', donnees, function (data) {
//            switch (data.type) {
//                case 'success':
//                    window.location.reload();
//                    break;
//                case 'error':
//                    $('#btnAddReglementSubmit').show();
//                    $('#loaderReglement').hide();
//                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + data.message});
//                    break;
//            }
//        }, 'json');
//    });
//
//    $('.btnDelReglement').on('dblclick', function () {
//        $.post(chemin + 'factures/delReglement/', {reglementId: $(this).closest('tr').attr('data-reglementid')}, function (data) {
//            switch (data.type) {
//                case 'success':
//                    window.location.reload();
//                    break;
//                case 'error':
//                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + data.message});
//                    break;
//            }
//        }, 'json');
//    });
//    $('.btnDelAcompte').on('dblclick', function () {
//        $.post(chemin + 'factures/delAcompte/', {acompteId: $(this).closest('tr').attr('data-acompteid')}, function (data) {
//            switch (data.type) {
//                case 'success':
//                    window.location.reload();
//                    break;
//                case 'error':
//                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + data.message});
//                    break;
//            }
//        }, 'json');
//    });
//
//    $('.addAcompteFactureId').on('change', function () {
//        var element = $(this);
//        $.post(chemin + 'factures/acompteAffectation', {acompteId: element.closest('tr').attr('data-acompteid'), factureId: element.val()}, function (data) {
//            switch (data.type) {
//                case 'success':
//                    window.location.reload();
//                    break;
//                case 'error':
//                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + data.message});
//                    break;
//            }
//        }, 'json');
//    });

    /* -- Reglements -- */
//    $('#addReglementObjet').on('change', function () {
//        $.post(chemin + 'factures/resteAPayer', {factureId: $(this).val()}, function (data) {
//            if (data.type == 'success') {
//                $('#addReglementTotal').val(data.RAP);
//            }
//        }, 'json');
//    });
//    
//    $('#btnSendEmail').on('dblclick', function(){
//        
//        $.post(chemin + 'chiffrages/sendDevisByEmail/', {}, function(retour){
//            if (retour.type == 'success') {
//                $.toaster({priority: 'success', title: '<strong><i class="far fa-hand-peace"></i> OK</strong>', message: '<br>' + 'Devis envoyé'});
//            } else {
//                $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
//            }
//        }, 'json');
//    });
});

