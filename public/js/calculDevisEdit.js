$('document').ready(function () {

    $('.presta').on('click', function () {
        //Propriétés
        let id = $(this).attr('id');
        let divQuantite = $('#'+id+'_quantite');
        let divPrix = $('#'+id+'_prix');
        let divTva = $('#'+id+'_tva');
        let divMontantHt = $('#'+id+'_montant');
        let divMontantTtc = $('#'+id+'_motantTtc');

        let quantite = divQuantite.val();
        let prix = divPrix.val();
        let tva = divTva.val();

        //Calcul
        let montantHt = quantite * prix;
        let montantTtc = (montantHt*tva)/100 + montantHt;

        divMontantHt.val(montantHt.toFixed(2));
        divMontantTtc.val(montantTtc.toFixed(2));

        let totalHt = 0;
        let totalTtc = 0;
        for (let i = 0; i <= $('.presta').length+1; i++){
            let montantHt = +$('#devis_description_' + i + '_montant').val();
            let cleanMontantHt = isNaN(montantHt) ? 0 : montantHt;
            totalHt += cleanMontantHt;

            let montantTtc = +$('#devis_description_' + i + '_motantTtc').val();
            let cleanMontantTtc = isNaN(montantTtc) ? 0 : montantTtc;
            totalTtc += cleanMontantTtc;
        }
        console.log(totalTtc);
        $('#devis_montantHt').val(totalHt.toFixed(2));
        $('#devis_montantTtc').val(totalTtc.toFixed(2));
    });
});