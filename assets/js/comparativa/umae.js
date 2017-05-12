function cmbox_comparativa() {
    var id_destino = document.getElementById('comparativa').value;
    if (id_destino == "") {
        $('#area_comparativa').html('');
        $('#area_reportes').css('display', 'none');
    } else {
        var destino = site_url + '/comparativa/umae_tipo_curso';
        if (id_destino == 2) {
            destino = site_url + '/comparativa/umae_perfil';
        }
        var delegacion = document.getElementById('delegacion').value;
        $.ajax({
            url: destino
            , method: "post"
            , data: {delegacion: delegacion, vista: 1}
            , error: function () {
                console.warn("No se pudo realizar la conexión");
            }
            , beforeSend: function (xhr) {
                $('#area_comparativa').html('');
                mostrar_loader();
            }
        }).done(function (response) {
            $('#area_comparativa').html(response);
            $('#area_graph').html('');
            $('#area_reportes').css('display', 'none');
            ocultar_loader();
        });
    }
}

function cmbox_region() {
    var id_region = document.getElementById('region').value;
    $.ajax({
        url: site_url + "/buscador/get_delegaciones/" + id_region
        , method: "post"
        , error: function () {
            console.warn("No se pudo realizar la conexión");
            ocultar_loader();
        }
        , beforeSend: function (xhr) {
            mostrar_loader();
        }
    }).done(function (response) {
        $('#delegacion').empty()
        var opts = $.parseJSON(response);
        $('#delegacion').append('<option value="">Seleccionar...</option>');
        // Use jQuery's each to iterate over the opts value
        $.each(opts, function (i, d) {
            $('#delegacion').append('<option value="' + d.id_delegacion + '">' + d.nombre + '</option>');
        });
        ocultar_loader();
    });
}

function cmbox_delegacion() {
    if (document.getElementById('nivel') != null) {
        var nivel = document.getElementById('nivel').value;
        var delegacion = document.getElementById('delegacion').value;
        if (delegacion != null && delegacion != "") {
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
                $('#unidad1').empty();
                $('#unidad1').append('<option value="">Seleccionar...</option>');
                $('#unidad2').empty();
                $('#unidad2').append('<option value="">Seleccionar...</option>');               
                ocultar_loader();
            });
        }
    }
}

function cmbox_tipo_unidad() {
    var delegacion = document.getElementById('delegacion').value;
    var tipo_unidad = document.getElementById('tipo_unidad').value;
    var umae = 1;
    $.ajax({
        url: site_url + "/buscador/get_unidades/" + umae
        , method: "post"
        , data: {delegacion:delegacion, tipo_unidad:tipo_unidad}
        , error: function () {
            console.warn("No se pudo realizar la conexión");
        }
        , beforeSend: function (xhr) {
            mostrar_loader();
        }
    }).done(function (response) {
        $('#unidad1').empty()
        $('#unidad2').empty()
        var opts = $.parseJSON(response);
        $('#unidad1').append('<option value="">Seleccionar...</option>');
        $('#unidad2').append('<option value="">Seleccionar...</option>');
        $('#unidad2').append('<option value="0">PROMEDIO</option>');
        // Use jQuery's each to iterate over the opts value
        $.each(opts, function (i, d) {
            $.each(d, function(j, data){            
                $('#unidad1').append('<option value="' + j + '">' + data + '</option>');
                $('#unidad2').append('<option value="' + j + '">' + data + '</option>');
            });
        });       
        ocultar_loader();
    });
}