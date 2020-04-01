/**
 * Permet d'afficher le nombre de client dans des badges
 */
$.ajax({
   type:"GET",
   dataType:'json',
   url: url,
   success: function(data){
        console.log(data);
        let cardBody = $('.card-footer');
        cardBody.append("<span class='badge badge-success mr-3 p-2'>"+data.client.length + " clients </span>");
        cardBody.append("<span class='badge badge-primary  p-2'>"+data.facture.length + " factures </span>");
   }
});