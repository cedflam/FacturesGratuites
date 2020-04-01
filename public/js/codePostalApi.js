/* Ce code n'est pas actif mais juste à titre d'exemple car  il réside un problème avec le choiceType de Symfony*/
//Permet de récuperer une ville par le code postal depuis une api
$('#entreprise_cp').on('change', function(){
    let cp = $('#entreprise_cp').val();
    axios.get("https://geo.api.gouv.fr/communes?codePostal=" + cp)
         .then(function(response){
            let ville = $('#entreprise_ville');
            let villeContent = $('#entreprise_ville option');
            villeContent.remove();
            for(let i = 0; i < response.data.length; i++){
                ville.append("<option>" + response.data[i].nom + "</option>");
            }
        })
});