$('document').ready(function () {
    /**
     * Permet d'afficher un diagramme de résultats avec google charts
     * @type {jQuery|string|undefined}
     */
//je récupère l'id
    let url = $('.idEntreprise').val();

//Requete ajax
    $.ajax({
        type: "GET",
        dataType: 'json',
        url: url,
        success: function (data) {
            //Propriétés
            let totalTtc = 0;
            let totalCrd = 0;
            let totalAcompte = 0;

            //je boucle sur les factures
            for (let i = 0; i < data.facture.length; i++) {
                //Je stocke les montants courants dans des variables
                let montantTtc = data.facture[i].montantTtc;
                let crd = data.facture[i].crd;
                let acompte = data.facture[i].totalAcompte;
                //Calculs
                totalTtc += montantTtc;
                totalCrd += crd;
                totalAcompte += acompte;

            }

            //Chart
            google.charts.load('current', {'packages': ['bar']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Année', "Chiffre d'affaire", 'Encaissé', 'Restant dû'],
                    ['2020', totalTtc, totalAcompte, totalCrd],

                ]);

                var options = {
                    chart: {
                        title: "Chiffre d'affaire",

                    }
                };
                var chart = new google.charts.Bar(document.getElementById('chiffres'));
                chart.draw(data, google.charts.Bar.convertOptions(options));
            }

        },
        error: function () {
            console.log("une erreur s'est produite");
        }

    });
});