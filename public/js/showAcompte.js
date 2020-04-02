//Propriétés
let showAcompte = $('.show-acompte');
console.log(showAcompte);
let modalShow = $('.show-body');
console.log(modalShow);
/**
 * Permet de modifier un acompte depuis une modal
 */
showAcompte.on('click', function () {
    //J'initialise la modal = vide
    modalShow.text('');
    //Je définis l'url
    let url = $(this).attr('href');
    console.log(url);
    //requete ajax
    $.get(url, function (data) {
        console.log(data);
        //J'ajoute les données dans la modal
        modalShow.append(data);
        //J'ajoute l'url à l apropriété action du formaulaire
        $('form').attr('action', url );

    });

});


