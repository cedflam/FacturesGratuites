$('document').ready(function () {
    /**
     * Fonction permet d'effectuer l'ensemble des calculs sur le formulaire
     */
    $('#add-description').on('click', function () {
        //Je définis l'index
        let index = $('.presta').length - 1;
        //Je stocke les id
        let prix = $('#devis_description_' + index + '_prix');
        let tva = $('#devis_description_' + index + '_tva');


        //J'ajoute les classes au à l'ajout d'un description
        prix.addClass('prix_' + index);
        tva.addClass('tva_' + index);

        //Je déclenche le calcul au clic sur l'ajout d'une prestation
        $('.prix_' + index).on('click', function () {

            let id = $(this).attr('id');
            let target = $('#' + id);

            //Au changement de prix unitaire je lance le calcul
            $(this).on('keyup', function () {

                let prix = target.val();
                let quantite = $('#devis_description_' + index + '_quantite').val();
                let tva = $('#devis_description_' + index + '_tva').val();

                //Calcul HT
                let divMontantHt = $('#devis_description_' + index + '_montant');
                let montantHt = prix * quantite;
                divMontantHt.val(montantHt.toFixed(2));


                //Calcul TTC
                let divMontantTtc = $('#devis_description_' + index + '_motantTtc');
                let montantTtc = ((montantHt * tva) / 100) + montantHt;
                divMontantTtc.val(montantTtc.toFixed(2));

            });
        });

        // Si je clique sur la liste déroulante TVA je déclenche la fonction
        $('.tva_' + index).on('click', function () {

            let id = $(this).attr('id');
            let target = $('#' + id);
            //Je déclenche une nouvelle function qui calcul le prix avec la TVA
            $(this).on('click', function () {

                let tva = target.val();
                let quantite = $('#devis_description_' + index + '_quantite').val();
                let prix = $('#devis_description_' + index + '_prix').val();

                //Calcul TTC
                let montantHt = prix * quantite;
                let divMontantTtc = $('#devis_description_' + index + '_motantTtc');
                let montantTtc = ((montantHt * tva) / 100) + montantHt;
                divMontantTtc.val(montantTtc.toFixed(2));

            });
        });

    });

//Propriété
    let body = $('body');
    /**
     * Permet de calculer les totaux à chaque pression sur une touche
     */
    body.on('keyup', function () {
        let index = $('.presta').length - 1;
//Je boucle sur les montants HT des sous formulaires
        let totalHt = 0;
        for (let i = 0; i <= index; i++) {
            totalHt += +$('#devis_description_' + i + '_montant').val();
        }
        $('#devis_montantHt').val(totalHt.toFixed(2));

//Je boucle sur les montants TTC des sous formulaires
        let totalTtc = 0;
        for (let i = 0; i <= index; i++) {
            totalTtc += +$('#devis_description_' + i + '_motantTtc').val();
        }
        $('#devis_montantTtc').val(totalTtc.toFixed(2));
    });

    /**
     * Permet de calculer les totaux à chaque click
     */
    body.on('click', function () {
        //Index
        let index = $('.presta').length + 1;

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
        for (let i = 0; i <= index; i++) {
            let montantTtc = +$('#devis_description_' + i + '_motantTtc').val();
            let cleanMontantTtc = isNaN(montantTtc) ? 0 : montantTtc;
            totalTtc += cleanMontantTtc;
        }
        montantTotalTtc.val(totalTtc.toFixed(2));

    });
});







