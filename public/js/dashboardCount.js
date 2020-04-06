$('document').ready(function () {

    /**
     * Permet d'afficher le nombre de client dans
     */

    let url = $('.idEntreprise').val();

    $.ajax({
        type: "GET",
        dataType: 'json',
        url: url,
        success: function (data) {
            console.log(data);
            let cardBody = $('.card-body');
            cardBody.append("<span class='h2' >Clients : " + data.client.length + " </span> <br>");
            cardBody.append("<span class='h2'>Devis : " + data.facture.length + "</span>");
        }
    });
});