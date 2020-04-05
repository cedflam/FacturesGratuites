$('document').ready(function () {
console.log('ready');
//Propriétés
    let editFacture = $('.edit-facture');
    let modal = $('.edit-facture-body');
    /**
     * Permet d'ajouter un acompte depuis une modal
     */
    editFacture.on('click', function () {
        console.log('click');
        modal.text('');
        //Je définis l'url
        let url = $(this).attr('href');
        console.log(url);
        //requete ajax
        $.get(url, function (data) {
            modal.append(data);
        });
    });
});