/**
 * Fonction permet d'effectuer l'ensemble des calculs sur le formulaire
 */
$('#add-description').on('click', function () {

    let index = $('.presta').length-1;
    console.log('index = '+index);
    let quantite = $('#devis_description_' + index + '_quantite');
    let prix = $('#devis_description_' + index + '_prix');
    let tva = $('#devis_description_' + index + '_tva');
    let montantHt = $('#devis_description_' + index + '_montant');
    let montantTtc = $('#devis_description_' + index + '_motantTtc');

    quantite.addClass('quantite_' + index);
    prix.addClass('prix_' + index);
    tva.addClass('tva_' + index);
    montantHt.addClass('montantHt_' + index);
    montantTtc.addClass('motantTtc_' + index);

    //Je déclenche le calcul au clic sur l'ajout d'une prestation
    $('.prix_' + index).on('click', function () {
        let id = $(this).attr('id');
        let target = $('#' + id);
        console.log('target = '+target);

        //Au changement de prix unitaire je lance le calcul
        $(this).on('keyup', function () {
            let prix = target.val();
            let quantite = $('#devis_description_'+index+'_quantite').val();
            let divTva = $('#devis_description_'+index+'_tva').val();

            //Calcul HT
            let divMontantHt = $('#devis_description_'+index+'_montant');
            console.log(divMontantHt);
            let montantHt = prix * quantite;
            divMontantHt.val(montantHt.toFixed(2));

            //Calcul TTC
            let divMontantTtc = $('#devis_description_'+index+'_motantTtc');
            console.log(divMontantTtc);
            let montantTtc = ((montantHt * divTva ) /100) + montantHt;
            divMontantTtc.val(montantTtc.toFixed(2));


        });
    });

    // Si je clique sur la liste déroulante TVA je déclenche la fonction
    $('.tva_'+index).on('click', function () {
        let id = $(this).attr('id');
        let target = $('#' + id);
        //Je déclenche une nouvelle function qui calcul le prix avec la TVA
        $(this).on('click', function () {
            let tva = target.val();
            let quantite = $('#devis_description_'+index+'_quantite').val();
            let prix = $('#devis_description_'+index+'_prix').val();

            //Calcul TTC
            let montantHt = prix * quantite;
            let divMontantTtc = $('#devis_description_'+index+'_motantTtc');
            let montantTtc = ((montantHt * tva ) /100) + montantHt;
            divMontantTtc.val(montantTtc.toFixed(2));
        });
    });

    //Je boucle sur les montants HT des sous formulaires
    for (let i = 0; i <= index; i++){}

    //Je boucle sur les montants TTC des sous formulaires
    for (let i = 0; i <= index; i++){}
});







