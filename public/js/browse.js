$('document').ready(function () {
//règle le bug d'affichage sur l'upload d'image du formaulaire d'inscription
    $('.custom-file-input').on('change', function (e) {
        let inputFile = e.currentTarget;
        $(inputFile).parent()
            .find('.custom-file-label')
            .html(inputFile.files[0].name);
    });
});
