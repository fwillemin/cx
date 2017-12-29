var chemin = 'http://192.168.0.1/cx/index.php/';
var hold;
function refactor_date(timeDate, type) {
    type = type || 'input';
    if (timeDate > 0) {
        var refactor = new Date(timeDate * 1000);
        var jour_refactor = refactor.getDate();
        if (jour_refactor < 10) {
            jour_refactor = '0' + jour_refactor;
        }
        ;
        var mois_refactor = refactor.getMonth() + 1;
        if (mois_refactor < 10) {
            mois_refactor = '0' + mois_refactor;
        }
        ;
        if (type == 'input') {
            return refactor.getFullYear() + '-' + mois_refactor + '-' + jour_refactor;
        } else {
            return jour_refactor + '/' + mois_refactor + '/' + refactor.getFullYear();
        }
    } else {
        return '';
    }
}
function refactor_heure(timeDate) {
    var refactor = new Date(timeDate * 1000);
    var heure_refactor = refactor.getHours();
    if (heure_refactor < 10) {
        heure_refactor = '0' + heure_refactor;
    }
    ;
    var minute_refactor = refactor.getMinutes();
    if (minute_refactor < 10) {
        minute_refactor = '0' + minute_refactor;
    }
    ;
    return heure_refactor + ':' + minute_refactor;
}

/**
 * Injecte les villes correspondant à un Cp dans un champs HTML et selectionne celui indiqué par valeurSelect
 *
 * @param string cp Code postal à rechercher
 * @param string champVille Input de type SELECT HTML dans lequel injecter les villes sous forme de balise OPTION
 * @param string valeurSelect Valeur à passer en SELECTED parmi les OPTION insérés
 */
function injectVille(cp, champVille, valeurSelect) {
    $.post(chemin + 'clients/get_villes', {cp: cp}, function (data) {
        $('#' + champVille + ' option').remove();
        for (var i = 0; i < data.length; i++) {
            if (i == 0) {
                $('#addClientPays').val(data[i].pays);
            }
            $('#' + champVille).append('<option value="' + data[i].ville + '" country="' + data[i].pays + '">' + data[i].ville + ' [' + data[i].pays + ']</option>');
        }
        if (valeurSelect) {
            $('#' + champVille + ' option[value="' + valeurSelect + '"]').prop('selected', true);
        }
    }, 'json');
}

function upperConvert(champs) {
    var valeur = $(champs).val().toUpperCase();
    $(champs).val(valeur);
}

function floatConvert(champs) {
    var valeur = parseFloat($(champs).val().replace(",", "."));
    if (isNaN(valeur)) {
        $(champs).val('');
    } else {
        $(champs).val(valeur);
    }
}

function majMultiple(qte, multiple){
    var qte =  Math.round(Math.ceil(Math.round(qte / multiple * 100) / 100) * multiple * 100) / 100;
    console.log(qte);
    return qte;
}  

$(document).ready(function () {

    $('.tooltipOk').tooltip();
    $('[data-toggle="popover"]').popover();
    $(document).on('keyup', function () {
        hold = false;
    });
    $(document).on('keydown', function (e) {
        if (e.keyCode == 32) {
            hold = true;
        }
    });

    /**
     *  Partie client laissée en général car utilisée pour ajouter un client directement dans les devis et BDC
     */
    $('#btnAddClient').on('click', function () {
        $('#addClientNom').val('');
        $('#addClientRaisonSociale').val('');
        $('#addClientCodeComptable').val('');
        $('#addClientPrenom').val('');
        $('#addClientAdresse1').val('');
        $('#addClientAdresse2').val('');
        $('#addClientCp').val('');
        $('#addClientVille').val('');
        $('#addClientPays').val('FRANCE');
        $('#addClientTel').val('');
        $('#addClientPortable').val('');
        $('#addClientFax').val('');
        $('#addClientEmail').val('');
        $('#addClientIntracom').val('');
        $('#addClientModeReglement option[value="0"]').prop('selected', true);
        $('#addClientExo option[value="0"]').prop('selected', true);
        $('#addClientConditionReglement option[value="1"]').prop('selected', true);
        $('.has-success').attr('class', 'form-group has-error');
        $('#modalAddClient h4').text('Ajouter un client');
        $('#btnAddClientSubmit').text('Ajouter');
        $('#modalAddClient').modal('show');
    });

    $('.requiredField').on('change', function () {
        var elem = $(this).closest('.form-group');
        if ($(this).val() != '') {
            if ($(this).attr('id') == 'addClientCp' && $('#addClientVille').val() === null) {
                elem.attr('class', 'form-group has-error');
            } else {
                elem.attr('class', 'form-group has-success');
            }
        } else {
            elem.attr('class', 'form-group has-error');
        }
    });

    $('#formAddClient').on('submit', function (e) {
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'clients/addClient', donnees, function (retour) {
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
    
    
    /**
     * - Ajout d'une facture
     * - Affichage BL
     * laissés en commun car utilisés en vente et en administration
     */
    $('#btnAddFacture, .btnAddFacture').on('dblclick', function () {        
        var aFacturer = [];
        $('.blAFacturer').each(function () {
            if ($(this).prop('checked')) {
                aFacturer.push($(this).val());
            }
        }).promise()
                .done(
                        $.post(chemin + 'factures/addFacture', {bls: aFacturer, clientId: $(this).attr('client'), bdcId: $(this).attr('bdc'), crId: $(this).attr('crId')}, function (data) {
                            switch (data.type) {
                                case 'success':
                                    window.open(chemin + 'documents/editionFacture/' + data.factureId);
                                    window.location.reload();
                                    break;
                                case 'error':
                                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + data.message});
                                    break;
                            }
                        }, 'json')
                        );
    });
    
    $('.btnAffBl').on('click', function () {
        if (!hold || hold === false) {
            window.open(chemin + 'documents/editionBl/' + $(this).attr('data-blid'));
        } else {
            window.open(chemin + 'documents/editionBl/' + $(this).attr('data-blid') + '/nonChiffe');
        }
    });

    /**
     *  Affichage de la session avec ESP+ESC
     */
    $(document).on('keydown', function (e) {
        if (e.keyCode == 27 && hold === true) {
            $('#modalSession').modal('show');
        }
    });


    /* -- Devis -- */

//    $('.devisLigne').on('click', function () {
//        var devis = $(this).attr('id');
//        window.location.assign(chemin + 'ventes/reloadDevis/' + devis);
//    });
//    $('.btnDelDevis').on('click', function (e) {
//        e.stopPropagation();
//    });
    $('.btnDelDevis').on('dblclick', function () {
        var devis = $(this).closest('tr').attr('id');
        $.post(chemin + 'ventes/delDevis', {devis: devis}, function (data) {
            if (data.type == 'success') {
                window.location.assign(chemin + 'ventes/resetDevisEncours');
            }
        }, 'json');
    });

    /* -- Bon de commande -- */
    $('#rechClientBdc').on('keyup', function () {
        $(this).val($(this).val().toUpperCase());
        $('.ligneBdc').each(function () {
            if ($(this).children('td').eq(1).text().indexOf($('#rechClientBdc').val()) >= 0) {
                $(this).fadeIn();
            } else {
                $(this).fadeOut();
            }
        });
    });
    $('#rechBdcId').on('keyup', function () {
        $(this).val($(this).val().toUpperCase());
        $('.ligneBdc').each(function () {
            if ($(this).children('td').eq(0).text().indexOf($('#rechBdcId').val()) >= 0) {
                $(this).fadeIn();
            } else {
                $(this).fadeOut();
            }
        });
    });
    $('#btnBdcEnregistrer').on('click', function () {
        $.post(chemin + 'ventes/addBdc', {}, function (data) {
            $('.alert').remove();
            if (data.type == 'success') {
                window.location.assign(chemin + 'ventes/reloadBdc/' + data.bdc);
            } else {
                $('#btnBdcEnregistrer').before('<div class="alert alert-danger">' + data.message + '</div>');
            }
        }, 'json');
    });

    $('.ligneBdc').on('click', function () {
        window.location.assign(chemin + 'ventes/reloadBdc/' + $(this).closest('tr').attr('id'));
    });
    $('.btnDelBdc').on('click', function (e) {
        e.stopPropagation();
    });
    $('.btnDelBdc').on('dblclick', function () {
        var bdc = $(this).closest('tr').attr('id');
        $.post(chemin + 'ventes/delBdc', {bdc: bdc}, function (data) {
            if (data.type == 'success') {
                window.location.assign(chemin + 'ventes/bdcListe');
            }
        }, 'json');
    });

    /* -- appro Usine -- */
    /*
     $('.checkACommander').on('change',function() {
     /* recalcul de la qte totale à commander
     var totalACommander = 0;
     $('.checkACommander').each(function(){
     if( $(this).prop('checked') == true ){
     totalACommander += parseFloat($(this).closest('tr').children('td').eq(1).text());
     $('#addApproQte').text(totalACommander);
     $('#labelApproQte').text(totalACommander);
     }
     });
     $('#addApproQte').text(totalACommander);
     $('#labelApproQte').text(totalACommander);
     });
     $('#btnGenererApproProduit').on('click',function() {
     var totalACommander = 0;
     var articles = new Array();
     $('.checkACommander').each(function(){
     if( $(this).prop('checked') == true ){
     totalACommander += parseFloat($(this).closest('tr').children('td').eq(1).text());
     articles.push($(this).val());
     }
     });
     if(totalACommander > 0){
     $.post(chemin+'commandes/addAppro',{qte:totalACommander,articles:articles},function(data){
     if(data.type == 'success'){ window.location.reload(); }
     },'json');
     }
     });
     */
    function listeArticlesBdcSelectionnes() {
        var commandes = [];
        $('.checkAction').each(function () {
            if ($(this).prop('checked') === true) {
                commandes.push($(this).closest('tr').attr('id'));
            }
        });
        return commandes;
    }
    
    /* -- Commandes Usine -- */
    $('.commandeApproModQte').on('change', function () {
        $.post(chemin + 'commandes/modApproQte', {approId: $(this).attr('cible'), qte: $(this).val()}, function (data) {
            if (data.type == 'success') {
                window.location.reload();
            }
        }, 'json');
    });
    $('.btnCommandeVisualisation').on('click', function () {
        var usineId = $(this).attr('cible');
        $.post(chemin + 'commandes/visualisationCommande/' + usineId, {}, function (data) {
            if (data.type == 'success') {
                $('#modalCommandeVisualisation .modal-body').text('');
                $('#modalCommandeVisualisation .modal-body').prepend(data.message);
//                if(data.email != ''){ $('#modalCommandeVisualisation .modal-body').append('<div class="alert alert-success">Cette commande sera envoyée automatiquement par email à l\'adresse : <strong>'+data.email+'</strong></div>'); }
//                else { $('#modalCommandeVisualisation .modal-body').append('<div class="alert alert-danger">Vous devez envoyer cette commande manuellement.</div>'); }
                $('#btnAddCommande').attr('cible', usineId);
                $('#modalCommandeVisualisation').modal('show');
            }
        }, 'json');
    });
    $('#btnAddCommande').on('dblclick', function () {
        $.post(chemin + 'commandes/addCommande', {usineId: $(this).attr('cible')}, function (data) {
            $('#modalCommandeVisualisation .alert').remove();
            if (data.type == 'success') {
                $('#modalCommandeVisualisation .modal-body').text('');
                $('#modalCommandeVisualisation .modal-body').prepend('<div class="alert alert-success">Votre commande a été générée.</div>');
                $('#btnAddCommande').fadeOut();
            } else {
                $('#modalCommandeVisualisation .modal-footer').prepend('<div class="alert alert-danger">Erreur lors de l\'envoi. Vérifiez la cohérence de votre commande, votre connexion internet, et rechargez votre page.');
            }
        }, 'json');
    });
    $('#modalCommandeVisualisation').on('hidden.bs.modal', function () {
        window.location.reload();
    });
    $('.ligneCommande').on('click', function () {
        window.location.assign(chemin + 'commandes/ficheCommande/' + $(this).attr('id'));
    });
    $('.formReceptionCommande').on('submit', function (e) {
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'commandes/receptionAppro', donnees, function (data) {
            if (data.type == 'success') {
                window.location.reload();
            }
        }, 'json');
    });

});