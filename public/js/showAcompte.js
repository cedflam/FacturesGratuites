$('document').ready(function () {

//Propriétés
    let showAcompte = $('.show-acompte');
    let modalShow = $('.delete-acompte-body');

    /**
     * Permet de modifier un acompte depuis une modal
     */
    showAcompte.on('click', function () {
        console.log('click');
        //J'initialise la modal = vide
        modalShow.text('');
        //Je définis l'url
        let url = $(this).attr('href');
        //requete ajax
        $.get(url, function (data) {
            //J'ajoute les données dans la modal
            modalShow.append(data);
            //J'ajoute l'url à l apropriété action du formaulaire
            $('form').attr('action', url);

        });

    });

});


