$('document').ready(function () {

    /**
     * Permet d'afficher le nombre de client dans
     */

    let url = $('.idEntreprise').val();

    $.ajax({
        type: "GET",
        dataType: 'json',
        url: url,
        success: function (data) {
            console.log(data);
            let clients = data.client.length;

            //Chart
            google.charts.load('current', {'packages': ['bar']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Total', "Nb Clients"],
                    ['Objectif', 20],
                    ['CLients', clients]

                ]);
                var options = {
                    chart: {
                        title: 'Nombre de clients',
                    }
                };
                var chart = new google.charts.Bar(document.getElementById('clients'));
                chart.draw(data, google.charts.Bar.convertOptions(options));
            }

        },
        error: function () {
            console.log("une erreur s'est produite");
        }

    });


});