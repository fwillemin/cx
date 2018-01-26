$(document).ready(function () {

    $('#tableBdc').bootstrapTable({
        idField: 'bdcId',
        url: chemin + 'ventes/getAllBdc',
        pagination: true,
        search: true,
        cardView: false,
        showColumns: true,
        pageSize: 50,
        contextMenu: '#context-menu',
        onClickRow: function (row) {
            window.location.assign(chemin + 'ventes/reloadBdc/' + row.bdcId)
        },
        //onContextMenuItem: contextActionsBdc,
        columns: [[{
                    field: 'bdcId',
                    title: 'ID',
                    width: 30,
                    align: 'center',
                    sortable: true,
                    visible: true
                }, {
                    field: 'bdcDate',
                    title: 'Date',
                    formatter: dateFormatter,
                    sortable: true,
                    width: 150
                }, {
                    field: 'bdcClient',
                    title: 'Client',
                    width: 340,
                    sortable: true,
                    align: 'left'
                }, {
                    field: 'bdcVille',
                    title: 'Ville',
                    sortable: true,
                    width: 280
                }, {
                    field: 'bdcTotalHT',
                    title: 'Total HT',
                    width: 80,
                    align: 'right'
                }, {
                    field: 'bdcEtat',
                    title: 'Etat',
                    width: 90,
                    align: 'left',
                    formatter: etatFormatter
                }
            ]
        ]
    });

    $('#btnBdcLivres').on('click', function () {
        $('#tableBdc').bootstrapTable('removeAll');
        $('#tableBdc').bootstrapTable('refresh', {url: chemin + 'ventes/getAllBdc/2'});
    });
    $('#btnBdcNonLivres').on('click', function () {
        $('#tableBdc').bootstrapTable('removeAll');
        $('#tableBdc').bootstrapTable('refresh', {url: chemin + 'ventes/getAllBdc'});
    });
    $('#btnBdcLivres6M').on('click', function () {
        $('#tableBdc').bootstrapTable('removeAll');
        $('#tableBdc').bootstrapTable('refresh', {url: chemin + 'ventes/getAllBdc/1'});
    });

    /* Formatage des cellules de la table */
    function dateFormatter(value) {
        return refactor_date(value, 'human');
    }
    function etatFormatter(value) {
        switch (value) {
            case '0':
                retour = '<span class="label label-default">Attente de livraison</span>';
                break;
            case '1':
                retour = '<span class="label label-warning">Partiellement livré</span>';
                break;
            case '2':
                retour = '<span class="label label-success">Livré</span>';
                break;
        }
        return retour;
    }

    /* ----- DATE et USER ----- */
    $('#venteDate').on('change', function () {
        $.post(chemin + 'ventes/venteDateChange', {venteDate: $(this).val()}, function (data) {

            if (data.type == 'success') {
                window.location.reload();
            } else {
                $('#venteDate').before('<div class="alert alert-danger">Format de date invalide : AAAA-MM-JJ</div>');
            }
        }, 'json');
    });
    $('#venteCollaborateurId').on('change', function () {
        console.log('Changement de collabo');
        $.post(chemin + 'ventes/venteCollaborateurChange', {venteCollaborateurId: $(this).val()}, function (data) {
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
        $.post(chemin + 'ventes/venteClientChange', {clientId: clientId}, function (data) {
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
        $.post(chemin + 'ventes/addCartArticle', donnees, function (data) {
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
        $.post(chemin + 'ventes/delCartArticle', {rowid: rowid}, function (data) {
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
        $.post(chemin + 'ventes/getCartArticle', {rowid: rowid}, function (data) {
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

    $('#venteCommentaire').on('change', function () {
        var elem = $(this);
        $.post(chemin + 'ventes/venteChange', {venteOption: 'Commentaire', venteValeur: $(this).val()}, function (data) {

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
    $('#btnBdcReset').on('dblclick', function () {
        window.location.assign(chemin + 'ventes/resetDevisEncours');
    });

    $('#btnDelBdc').confirm({
        title: 'Suppression du bon de commande',
        content: 'Confirmez-vous la suppression de ce bon de commande ?',
        type: 'purple',
        columnClass: 'medium',
        buttons: {
            confirm: {
                text: 'Oui, supprimer',
                btnClass: 'btn-green',
                action: function () {
                    $.post(chemin + 'ventes/deleteBdc/', {bdcId: $('#btnDelBdc').attr('data-bdcid')}, function (data) {
                        switch (data.type) {
                            case 'success':
                                window.location.assign(chemin + 'ventes/reloadBdc/' + $('#btnDelBdc').attr('data-bdcid'));
                                break;
                            case 'error':                                
                                $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + data.message});
                                break;
                        }
                    }, 'json');
                }
            },
            cancel: {
                text: 'Annuler',
                action: function () {

                }
            }
        }
    });
    
    $('#btnReanimateBdc').confirm({
        title: 'Annuler la suppression du bon de commande',
        content: 'Confirmez-vous vouloir annuler la suppression de ce bon de commande ?',
        type: 'purple',
        columnClass: 'medium',
        buttons: {
            confirm: {
                text: 'Oui',
                btnClass: 'btn-green',
                action: function () {
                    $.post(chemin + 'ventes/reanimateBdc/', {bdcId: $('#btnReanimateBdc').attr('data-bdcid')}, function (data) {
                        switch (data.type) {
                            case 'success':
                                window.location.assign(chemin + 'ventes/reloadBdc/' + $('#btnReanimateBdc').attr('data-bdcid'));
                                break;
                            case 'error':                                
                                $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + data.message});
                                break;
                        }
                    }, 'json');
                }
            },
            cancel: {
                text: 'Annuler',
                action: function () {

                }
            }
        }
    });

    /* -- Livraison -- */
    $('#btnModalBl').on('click', function () {
        $('#modalLivraison').modal('show');
    });
    $('#modalLivraison').on('hidden.bs.modal', function () {
        window.location.assign(chemin + 'ventes/reloadBdc/' + $('#idVenteLivree').val());
    });
    $('#btnAddBl').on('dblclick', function () {
        console.log('start');
        var livraison = [];
        $('.articlesALivrer').each(function () {
            article = [];
            if ($(this).children('td').eq(3).find('.qteALivrer').val() != '0') {
                article.push($(this).attr('id'));
                article.push($(this).children('td').eq(3).find('.qteALivrer').val());
                article.push($(this).children('td').eq(4).find('.stockADeduire').val());
                livraison.push(article);
            }
        }).promise().done(
                function () {
                    console.log('table complete');
                    $.post(chemin + 'livraisons/addBl', {livraisons: livraison, bdcId: $('#idVenteLivree').val()}, function (data) {
                        console.log('Retour');
                        $('#modalLivraison .alert').remove();
                        if (data.type == 'success') {
                            $('#btnAddBl').fadeOut();
                            $('#livraisonTable').before('<div class="alert alert-success">Un Bl est enregistré pour ce bon de commande.</div>');
                            $('#livraisonTable').remove();
                        } else {
                            $.toaster({priority: 'danger', title: '<strong><i class="fas fa-warning"></i> Oups</strong>', message: '<br>' + data.message});
                        }

                    }, 'json');
                }
        );
    });

    $('.delBl').on('dblclick', function () {
        var elem = $(this);
        $.post(chemin + 'livraisons/delBl', {blId: elem.attr('cible')}, function (data) {
            if (data.type == 'success') {
                window.location.assign(chemin + 'ventes/reloadBdc/' + elem.attr('bdc'));
            }
        }, 'json');
    });

    /* -- Facture --*/

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


    /* -- Reglements -- */
    $('#addReglementObjet').on('change', function () {
        if ($(this).val() == '0') {
            $('#addReglementMontant').val('');
        } else {
            $.post(chemin + 'factures/resteAPayer', {factureId: $(this).val()}, function (data) {
                switch (data.type) {
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

    $('.reaffecteReglement').on('change', function () {
        $.post(chemin + 'factures/affecteReglementAFacture', {reglementId: $(this).closest('tr').attr('data-reglementid'), factureId: $(this).val()}, function (retour) {
            switch (retour.type) {
                case 'success':
                    window.location.reload();
                    break;
                case 'error':
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-warning"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
            }
        }, 'json');
    });

    function reglementRAZ() {
        $('#addReglementId').val('');
        $('#addReglementMontant').val('');
        $('#addReglementMotif').val('');
        $('#addReglementMotif').hide();
        $('#btnAddReglementSubmit').attr('class', 'btn btn-sm btn-primary');
        $('#btnAddReglementSubmit').html('<i class="glyphicon glyphicon-piggy-bank"></i> Payer');
        $('#btnAddReglementCancel').hide();
    }

    $('#btnAddReglementCancel').on('click', function () {
        reglementRAZ()
    });

    $('.btnModReglement').on('click', function () {

        reglementRAZ();
        $('#addReglementMotif').show();
        $('#btnAddReglementCancel').show();
        $('#btnAddReglementSubmit').html('<i class="fas fa-pencil-alt"></i> Modifier');
        $('#btnAddReglementSubmit').attr('class', 'btn btn-sm btn-danger');
        $('#addReglementId').val($(this).closest('tr').attr('data-reglementid'));
        $('#addReglementMontant').val($(this).closest('tr').attr('data-reglementMontant'));
        $('#addReglementMode option[value="' + $(this).closest('tr').attr('data-reglementmodeid') + '"]').prop('selected', true);
        $('#addReglementObjet option[value="' + $(this).closest('tr').children('td').eq('3').find('select').val() + '"]').prop('selected', true);
    });

    $('#btnSendBdcEmail').on('dblclick', function () {
        $.post(chemin + 'ventes/sendBdcByEmail/', {}, function (retour) {
            if (retour.type == 'success') {
                $.toaster({priority: 'success', title: '<strong><i class="far fa-hand-peace"></i> OK</strong>', message: '<br>' + 'Bon de commande envoyé'});
            } else {
                $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
            }
        }, 'json');
    });

    $('.btnSendBlEmail').on('dblclick', function () {
        $.post(chemin + 'livraisons/sendBlByEmail/', {blId: $(this).closest('tr').attr('data-blid')}, function (retour) {
            if (retour.type == 'success') {
                $.toaster({priority: 'success', title: '<strong><i class="far fa-hand-peace"></i> OK</strong>', message: '<br>' + 'Bon de livraison envoyé'});
            } else {
                $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
            }
        }, 'json');
    });

    $('.btnSendFactureEmail').on('dblclick', function () {
        $.post(chemin + 'factures/sendFactureByEmail/', {factureId: $(this).closest('tr').attr('data-factureid')}, function (retour) {
            if (retour.type == 'success') {
                $.toaster({priority: 'success', title: '<strong><i class="far fa-hand-peace"></i> OK</strong>', message: '<br>' + 'Facture envoyée'});
            } else {
                $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
            }
        }, 'json');
    });

});

