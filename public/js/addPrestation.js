$('#add-description').on('click', function () {

    const div = $('#devis_description');
    const index = $('.presta').length;
    const button = $('.delete');
    //Je récupère le prototype des entrées
    const tmpl = div.data('prototype').replace(/__name__/g, index);
    //j'injecte le code dans la div
    div.append(tmpl);

    let target = 'block_devis_description_'+index;
    button.attr('id', target);
    console.log(target);
    console.log(button.attr('id'));

    handleDeleteButton(button, target);

});

function handleDeleteButton(){
    $('.delete').on('click', function (button, target) {
        if(button === target){
           console.log( $('#'+target).remove());
        }
    })
}
