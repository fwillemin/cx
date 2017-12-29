$(document).ready(function () {

    $('#formPdv').on('submit', function (e) {
        e.preventDefault();
        var donnees = $(this).serialize();
        $.post(chemin + 'settings/majPdv', donnees, function (retour) {
            switch (retour.type) {
                case 'success':
                    $.toaster({priority: 'success', title: '<strong><i class="fa fa-hand-peace-o"></i> OK</strong>', message: '<br>' + 'Paramètres modifiés'});
                    break;
                default:                                        
                    $.toaster({priority: 'danger', title: '<strong><i class="fas fa-exclamation-triangle"></i> Oups</strong>', message: '<br>' + retour.message});
                    break;
            }
        }, 'json');
    });

});