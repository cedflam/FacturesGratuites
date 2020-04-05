$('document').ready(function () {
    /**
     * Permet d'afficher une fiche client en récupérant les
     * données qui lui sont liées
     */
    $('.voir').on('click', function () {
        //Je récupère le href du bouton cliqué
        let url = $(this).attr('href');
        console.log('url = ' + url);
        //Requete ajax
        $.ajax({
            type: "GET",
            dataType: "json",
            url: url,
            success: function (data) {
                console.log(data);
                //Propriétés
                let modalTitle = $('.modal-title');
                let modalBody = $('.modal-body');
                $('#btn-nouveau-devis').attr('href', "/devis/new/" + data.id);
                $('#liste-devis').attr('href', "/devis/show/" + data.id);
                //Je vide le texte de modalTitle
                modalTitle.text('');
                //J'ajoute les nouvelles données
                modalTitle.append('<h2><i class="fas fa-user"></i> ' + data.nom + ' ' + data.prenom + '</h2>');
                //Je vide le texte de modalBody
                modalBody.text('');
                //J'ajoute les nouvelles données
                modalBody.append("<h4>Coordonnées</h4>");
                modalBody = modalBody.append("<ul></ul>");
                modalBody.append('<li><strong>Email : </strong> ' + data.email + '</li>');
                modalBody.append('<li><strong>Téléphone : </strong> ' + data.tel + '</li>');
                modalBody.append('<li><strong>Adresse : </strong> ' + data.adresse + '</li>');
                modalBody.append('<li><strong>CP / Ville : </strong> ' + data.cp + ' ' + data.ville + '</li>');
                modalBody.append('<hr>');
                modalBody = $('.modal-body');
                modalBody.append("<h4>Dernières Factures</h4><br>");
                //Je boucle sur les factures
                let totalFactures = 0;
                for (let i = 0; i < data.facture.length; i++) {
                    //Propriétés
                    let montantTtc = data.facture[i].montantTtc;
                    let numFacture = data.facture[i].id;
                    totalFactures += montantTtc;
                    //Ajout des données
                    modalBody.append("<p><strong class='mr-3'>Facture n°" +
                        numFacture +
                        " : </strong>" +
                        montantTtc.toFixed(2) +
                        " €</p>");
                }
                modalBody.append("<hr><p><strong class='mr-5'>Total : </strong> " + totalFactures.toFixed(2) + " €</p>")
            },
            error: function (e) {
                alert("Une erreur s'est produite ! ");
            }
        });
    });
});

