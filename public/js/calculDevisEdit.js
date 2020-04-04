
$('.presta').on('click', function () {
    //Je récupère l'identifiant de la div courante
    let divId = $(this).attr('id');
   /* //Je récupère l'identifiant des div TotalHt et TotalTtc
    let inputTotalHt = $('#devis_montantHt');
    let inputTotalTtc = $('#devis_montantTtc');*/

    //Je récupère l'id des champs nécessaires
    let idQuantite = divId+'_quantite';
    let idTva = divId+'_tva';
    let idPrix = divId+'_prix';
    let idMontantHt = divId+'_montant';
    let idMontantTtc = divId+'_motantTtc';

    //Je stock les div dans des variables
    let divQuantite = $('#'+idQuantite);
    let divTva = $('#'+idTva);
    let divPrix = $('#'+idPrix);
    let divMontantHt = $('#'+idMontantHt);
    let divMontantTtc = $('#'+idMontantTtc);
    //J'ajoute les classes nécessaires
    divQuantite.addClass(idQuantite);
    divTva.addClass(idTva);
    divPrix.addClass(idPrix);
    divMontantHt.addClass(idMontantHt);
    divMontantTtc.addClass(idMontantTtc);

    /**
     * Permet d'effectuer le calul de chaque prestation
     * au moment du changement de prix unitaire
     */
    divPrix.on('keyup', function () {
        //Je récupère les valeurs
        let quantite = $('.'+idQuantite).val();
        let tva = $('.'+idTva).val();
        let prix = $(this).val();
        console.log(prix);
        //Calcul
        let totalHt = quantite * prix;
        let totalTtc = ((totalHt * tva)/100) + totalHt;
        divMontantHt.val(totalHt.toFixed(2));
        divMontantTtc.val(totalTtc.toFixed(2));

    });

    divTva.on('click', function () {
        //Je récupère les valeurs
        let quantite = $('.'+idQuantite).val();
        let tva = $('.'+idTva).val();
        let prix = $('.'+idPrix).val();
        //Calcul
        let totalHt = quantite * prix;
        let totalTtc = ((totalHt * tva)/100) + totalHt;
        divMontantTtc.val(totalTtc.toFixed(2));
    });

});

/**
 * Fonctionne sur les div ajoutées
 */
$('#add-description').on('click', function () {
    //Je stock l'index courant
    let index = $('.presta').length-1;
    //Je récupère l'id de la div
    let idDiv = 'devis_description_'+index;
    let divPresta = $('#'+idDiv);
    divPresta.addClass(idDiv);

    let idQuantite = idDiv+'_quantite';
    let idTva = idDiv+'_tva';
    let idPrix = idDiv+'_prix';
    let idMontantHt = idDiv+'_montant';
    let idMontantTtc = idDiv+'_motantTtc';
    console.log('idMontantHt = '+ idMontantHt);
    //Je stock les div dans des variables
    let divQuantite = $('#'+idQuantite);
    let divTva = $('#'+idTva);
    let divPrix = $('#'+idPrix);
    let divMontantHt = $('#'+idMontantHt);
    let divMontantTtc = $('#'+idMontantTtc);
    console.log('divMontantHt = '+divMontantHt);
    //J'ajoute les classes nécessaires
    divQuantite.addClass(idQuantite);
    divTva.addClass(idTva);
    divPrix.addClass(idPrix);
    divMontantHt.addClass(idMontantHt);
    divMontantTtc.addClass(idMontantTtc);


    /**
     * Permet d'effectuer le calul de chaque prestation
     * au moment du changement de prix unitaire
     */
    divPrix.on('keyup', function () {
        //Je récupère les valeurs
        let quantite = $('.'+idQuantite).val();
        let tva = $('.'+idTva).val();
        let prix = $(this).val();
        console.log(prix);
        //Calcul
        let totalHt = quantite * prix;
        let totalTtc = ((totalHt * tva)/100) + totalHt;
        divMontantHt.val(totalHt.toFixed(2));
        divMontantTtc.val(totalTtc.toFixed(2));

    });

    divTva.on('click', function () {
        //Je récupère les valeurs
        let quantite = $('.'+idQuantite).val();
        let tva = $('.'+idTva).val();
        let prix = $('.'+idPrix).val();
        //Calcul
        let totalHt = quantite * prix;
        let totalTtc = ((totalHt * tva)/100) + totalHt;
        divMontantTtc.val(totalTtc.toFixed(2));
    });

});

/**
 * Permet de calculer les totaux à chaque click
 */
let body = $('body');

body.on('change', function () {
    //Index
    let index = $('.presta').length+1;

    //Je boucle sur les montants HT des sous formulaires
    let totalHt = 0;
    let montantTotalHt = $('#devis_montantHt');
    for (let i = 0; i <= index; i++) {
        let montantHt = +$('#devis_description_' + i + '_montant').val();
        let cleanMontantHt = isNaN(montantHt) ? 0 : montantHt;
        totalHt += cleanMontantHt;
    }
    montantTotalHt.val(totalHt.toFixed(2));

    //Je boucle sur les montants TTC des sous formulaires
    let totalTtc = 0;
    let montantTotalTtc = $('#devis_montantTtc');
    for (let i = 0; i <= index; i++){
        let montantTtc = +$('#devis_description_' + i + '_motantTtc').val();
        let cleanMontantTtc = isNaN(montantTtc) ? 0 : montantTtc;
        totalTtc += cleanMontantTtc;
    }
    montantTotalTtc.val(totalTtc.toFixed(2));

});




