function valida_filtros(formulario) {
    var valido = false
    switch (formulario) {
        case 'tipo_curso':
            valido = valida_filtros_aux(['tipo_curso', 'tipo_unidad', 'unidad1', 'unidad2', 'periodo']);
            break;
        case 'perfil':
            valido = valida_filtros_aux(['subperfil', 'tipo_unidad', 'unidad1', 'unidad2', 'periodo']);
            break;
    }
    return valido;
}

function valida_filtros_aux(campos) {
    var valido = true;
    for (i = 0; i < campos.length; i++) {
        if (document.getElementById(campos[i]) != null) {
            var value = document.getElementById(campos[i]).value;
            if (value == null || value == "") {
                valido = false;
            }
        } else {
            console.log('elemento no encontrado: ' + campos[i]);
            valido = false;
        }
    }
    return valido;
}

function cmbox_nivel() {
    var nivel = document.getElementById('nivel').value;
    var delegacion = document.getElementById('delegacion').value;
    if (nivel != null && nivel != "" && delegacion != null && delegacion != "") {
        var datos = {delegacion: delegacion, nivel: nivel};
        if (document.getElementById('umae')) {
            datos = {delegacion: delegacion, nivel: nivel, umae: 1};
        }
        $.ajax({
            url: site_url + "/buscador/get_tipo_unidad/"
            , method: "post"
            , data: datos
            , error: function () {
                console.warn("No se pudo realizar la conexión");
            }
            , beforeSend: function (xhr) {
                mostrar_loader();
            }
        }).done(function (response) {
            $('#tipo_unidad').empty()
            var opts = $.parseJSON(response);
            $('#tipo_unidad').append('<option value="">Seleccionar...</option>');
            // Use jQuery's each to iterate over the opts value
            $.each(opts, function (i, d) {
                $('#tipo_unidad').append('<option value="' + d.id_tipo_unidad + '">' + d.nombre + '</option>');
            });
            ocultar_loader();
        });
    }
}

function cmbox_delegacion(){
    
}