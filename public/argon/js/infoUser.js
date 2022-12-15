var departamentoUsuario = document.createElement('unidadUsuario');


$.ajax({
    url: '/infoUser',
    type: 'get',
    dataType: 'json',
    success: function (response) {
        console.log(response);
        $("#unidadUsuario").text(''+response[0].departamento.area+ ' - Unidad '+response[1].unidadcapacitacion);
    }
});



