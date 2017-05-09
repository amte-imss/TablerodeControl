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
        $.ajax({
            url: destino
            , method: "post"
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