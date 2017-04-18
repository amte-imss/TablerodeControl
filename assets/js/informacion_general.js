$( document ).ready(function() {
    //$('[data-toggle="tooltip"]').tooltip(); //Llamada a tooltip
    calcular_totales('informacion_general/calcular_totales', 'form_busqueda');
});

/**
 *	Método que muestra una imagen (gif animado) que indica que algo esta cargando
 *	@return	string	Contenedor e imagen del cargador.
 */
function calcular_totales(path, form_recurso) {
    var dataSend = $(form_recurso).serialize();
    $.ajax({
        url: path,
        data: dataSend,
        method: 'POST',
        beforeSend: function (xhr) {
            mostrar_loader();
        }
    })
    .done(function (response) {
        //console.log(response);
        if(response.total){
            
        }
        /*if (typeof callback !== 'undefined' && typeof callback === 'function') {
            $(elemento_resultado).html(response).promise().done(callback());
        } else {
            $(elemento_resultado).html(response);
        }*/
    })
    .fail(function (jqXHR, textStatus) {
        $(elemento_resultado).html("Ocurrió un error durante el proceso, inténtelo más tarde.");
        ocultar_loader();
    })
    .always(function () {
        ocultar_loader();
    });
}
