$('document').ready(function () {
    /**
     * Permet d'ajouter et de supprimer une prestation
     */
    $('#add-description').on('click', function () {
        const div = $('#devis_description');
        const index = $('.presta').length;
        //Je récupère le prototype des entrées
        const tmpl = div.data('prototype').replace(/__name__/g, index);
        //j'injecte le code dans la div
        div.append(tmpl);


        //Permet de supprimer une Prestation
        let buttonDelete = $('.delete');
        buttonDelete.on('click', function () {
            let id = $(this).data('target');
            $('#' + id).remove();
        });
    });
});

