/**
 * PErmet de rechercher un client par son nom de famille
 */
$('#btnSearch').on('click', function () {
    //Je supprime la classe alert
    $('.errorSearch').remove();
    //Prorpriétés
    let nom = $("#search").val();
    let url = "/client/search/" + nom;
    //Requete ajax
    $.ajax({
        type: "GET",
        url: url,
        success: function (data) {
            //Propriétés
            let nom = data;
            //Boucle sur les tr du tableau
            $.each($('table tbody tr'), function () {
                //Condition
                if ($(this).find(".nom").text().toLowerCase() === nom.toLowerCase()) {
                    $(this).show();
                } else {
                    $(this).addClass('hidden');
                }
            })
        },
        error: function () {
            $('div .form-group').append(
                "<p class='alert alert-danger errorSearch'> " +
                "<small>Le client recherché n'a pas été trouvé !<small>" +
                "<p>"
            );
            //Je supprime le résultat précédent
            $('table tbody tr').removeClass('hidden');
        }
    })

});