$('document').ready(function () {
//Propriétés
    let addAcompte = $('.addAcompte');
    let modal = $('.add-acompte-body');
    /**
     * Permet d'ajouter un acompte depuis une modal
     */
    addAcompte.on('click', function () {
        //J'initialise la modal = vide
        modal.text('');
        //Je définis l'url
        let url = $(this).attr('href');
        console.log(url);
        //requete ajax
        $.get(url, function (data) {
            //J'ajoute les données dans la modal
            modal.append(data);
            //J'ajoute l'url à l apropriété action du formaulaire
            $('form').attr('action', url);

        });

    });

});