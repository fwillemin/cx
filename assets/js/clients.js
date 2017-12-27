$(document).ready(function () {

    $('#tableClients').bootstrapTable({
        idField: 'clientId',
        url: chemin + 'clients/getAllClients',
        pagination: true,
        search: true,
        cardView: false,
        showColumns: true,
        pageSize: 25,
        contextMenu: '#context-menu',
        onClickRow: function(row){ window.location.assign( chemin + 'clients/ficheClient/' + row.clientId)},
        //onContextMenuItem: contextActions,
        columns: [[{
                    field: 'clientId',
                    title: 'ID',
                    width: 40,
                    align: 'center'
                },{
                    field: 'clientType',
                    title: 'Type',
                    formatter: clientTypeFormatter,
                    width: 40,
                    align: 'center'
                }, {
                    field: 'clientRaisonSociale',
                    title: 'Raison sociale',
                    sortable: true,
                    width: 150
                }, {
                    field: 'clientNom',
                    title: 'Nom',
                    width: 150
                }, {
                    field: 'clientPrenom',
                    title: 'Prénom',
                    width: 100
                }, {
                    field: 'clientCp',
                    title: 'CP',
                    sortable: true,
                    width: 40
                }, {
                    field: 'clientVille',
                    title: 'Ville',
                    width: 150
                }, {
                    field: 'clientTel',
                    title: 'Téléphone',
                    width: 120
                }, {
                    field: 'clientPortable',
                    title: 'Portable',
                    width: 120
                }
            ]
        ]
    });

    /* Formatage des cellules de la table */

    function prixFormatter(value) {
        if (value) {
            return '€ ' + value;
        } else {
            return '';
        }
    }
    function clientTypeFormatter(value) {
        if (value == 1) {
            return '';
        } else {
            return '<i class="glyphicon glyphicon-bitcoin"></i>';
        }
    }
    function dateFormatter(value) {
        return refactor_date(value, 'human');
    }

    function arrayFormatter(value) {
        var text = '';
        for (i = 0; i < value.length; i++) {
            text += '-' + value[i] + '<br>';
        }
        return text;
    }

    /* Actions du menu contextuel sur les articles */
    function contextActions(row, $el) {
        switch ($el.data("item")) {

            case 'fiche':
                /* Accès à la fiche client */
                window.open(chemin + 'clients/ficheClient/' + row.clientId);
                break;
        }
    }

    /* ----------------------------------- */

    $('#btnModClient').on('click',function(){
        var elem = $(this);
        if(elem.hasClass('processing')) return;
        elem.addClass('processing');

        $.post( chemin + 'clients/getClient',{ clientId:$(this).attr('cible') }, function(data){
            if(data.type == 'success'){
                $('#addClientId').val(data.client.clientId);
                $('#addClientType option[value="'+data.client.clientType+'"]').prop('selected',true);
                $('#addClientCodeComptable').val(data.client.clientCodeComptable);
                $('#addClientNom').val(data.client.clientNom);
                $('#addClientRaisonSociale').val(data.client.clientRaisonSociale);
                $('#addClientPrenom').val(data.client.clientPrenom);
                $('#addClientAdresse1').val(data.client.clientAdresse1);
                $('#addClientAdresse2').val(data.client.clientAdresse2);
                $('#addClientCp').val(data.client.clientCp);
                $('#addClientVille').val(data.client.clientVille);
                $('#addClientPays').val(data.client.clientPays);
                $('#addClientTel').val(data.client.clientTel);
                $('#addClientPortable').val(data.client.clientPortable);
                $('#addClientFax').val(data.client.clientFax);
                $('#addClientEmail').val(data.client.clientEmail);
                $('#addClientIntracom').val(data.client.clientIntracom);
                $('#addClientExo option[value="' + data.client.clientExonerationTVA + '"]').prop('selected',true);
                $('#addClientModeReglement option[value="' + data.client.clientModeReglementId + '"]').prop('selected',true);
                $('#addClientConditionReglement option[value="' + data.client.clientConditionReglementId + '"]').prop('selected',true);
                $('.has-error').attr('class','form-group has-success');
                $('#modalAddClient h4').text('Modfier le client '+data.client.clientNom+' '+data.client.clientPrenom);
                $('#btnAddClientSubmit').text('Modifier');
                $('#modalAddClient').modal('show');
            }
            elem.removeClass('processing');
        },'json');
    });

    $('#btnDelClient').on('dblclick',function(){
        var client = $(this).attr('cible');
        $.post(chemin+'clients/delClient',{clientId:client},function(data){
            if(data.type == 'success'){
                window.location.assign(chemin+'clients');
            }
        },'json');
    });

});

