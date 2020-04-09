$('document').ready(function () {
    /**
     * Permet d'ajouter et de supprimer une prestation
     */
    var index = 0;
    $('#add-description').on('click', function () {

        index++;
        let div = $('#devis_description');

        //Je récupère le prototype des entrées
        let tmpl = div.data('prototype').replace(/__name__/g, index);
        //j'injecte le code dans la div
        div.append(tmpl);

        handleDeleteButton();
    });

   function handleDeleteButton(){
       //Permet de supprimer une Prestation
       let buttonDelete = $('.delete');
       buttonDelete.on('click', function () {
           console.log('click');
           let id = $(this).data('target');
           $('#' + id).remove();
       });
   }

   handleDeleteButton();

});

