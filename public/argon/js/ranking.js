
$(document).ready(function() {
    $.ajax({
        url: '/ranking',
        type: 'get',
        dataType: 'json',
        success: function(response) {
            if(response.recibidosHoy.length > 0) 
                
                $("#recibidosHoyTxt").text(''+response.recibidosHoy[0].total);
            if(response.masRecurrente.length > 0) 
                $("#masRecurrenteTxt").text(''+response.masRecurrente[0].servicio ?? 0);
            if(response.unidaMasEnvia.length > 0) 
                $("#unidaMasEnviaTxt").text(''+response.unidaMasEnvia[0].areaSolicitante ?? 0);
          
        }
    });
});

