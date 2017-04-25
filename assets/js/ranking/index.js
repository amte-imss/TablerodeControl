$(function () {
//    cmbox_region();
});

function cmbox_region() {
    var id_region = document.getElementById('region').value;
    $.ajax({
        url: site_url + "/buscador/get_delegaciones/" + id_region
        , method: "post"
        , error: function () {
            console.warn("No se pudo realizar la conexión");
        }
    }).done(function (response) {
        $('#delegacion').empty()
        var opts = $.parseJSON(response);
        $('#delegacion').append('<option value="">Seleccionar...</option>');
        // Use jQuery's each to iterate over the opts value
        $.each(opts, function (i, d) {
            $('#delegacion').append('<option value="' + d.id_delegacion + '">' + d.nombre + '</option>');
        });
    });
}

function cmbox_delegacion() {
    var id_delegacion = document.getElementById('delegacion').value;
    $.ajax({
        url: site_url + "/buscador/get_tipo_unidades_by_delegacion/" + id_delegacion
        , method: "post"
        , error: function () {
            console.warn("No se pudo realizar la conexión");
        }
    }).done(function (response) {
        $('#tipo_unidad').empty()
        var opts = $.parseJSON(response);
        $('#tipo_unidad').append('<option value="">Seleccionar...</option>');
        // Use jQuery's each to iterate over the opts value
        $.each(opts, function (i, d) {
            $('#tipo_unidad').append('<option value="' + d.id_tipo_unidad + '">' + d.nombre + '</option>');
        });
    });
}

function cmbox_tipo_unidad() {
    var tipo_unidad = document.getElementById('tipo_unidad').value;
    var id_delegacion = document.getElementById('delegacion').value;
    $.ajax({
        url: site_url + "/buscador/get_cursos_by_delegacion/" + id_delegacion + "/" + tipo_unidad
        , method: "post"
        , error: function () {
            console.warn("No se pudo realizar la conexión");
        }
    }).done(function (response) {
        $('#curso').empty()
        $('#curso').append('<option value="">Seleccionar...</option>');
        var opts = $.parseJSON(response);
        // Use jQuery's each to iterate over the opts value
        $.each(opts, function (i, d) {
            $('#curso').append('<option value="' + d.id_curso + '">' + d.nombre + '</option>');
        });
    });
}

function cmbox_curso() {
    var curso = document.getElementById('curso').value;
    $.ajax({
        url: site_url + "/ranking/get_data/"
        , method: "post"
        , data: {curso: curso}
        , error: function () {
            console.warn("No se pudo realizar la conexión");
        }
    }).done(function (response) {
        $('#area_ranking').html(response);
    });
}

function cmbox_region_umae() {
    var id_region = document.getElementById('region').value;
    $.ajax({
        url: site_url + "/buscador/get_tipo_unidades_by_region/" + id_region
        , method: "post"
        , error: function () {
            console.warn("No se pudo realizar la conexión");
        }
    }).done(function (response) {
        $('#tipo_unidad').empty()
        var opts = $.parseJSON(response);
        $('#tipo_unidad').append('<option value="">Seleccionar...</option>');
        // Use jQuery's each to iterate over the opts value
        $.each(opts, function (i, d) {
            $('#tipo_unidad').append('<option value="' + d.id_tipo_unidad + '">' + d.nombre + '</option>');
        });
    });
}

function cmbox_tipo_unidad_umae() {
    var tipo_unidad = document.getElementById('tipo_unidad').value;
    var id_region = document.getElementById('region').value;
    $.ajax({
        url: site_url + "/buscador/get_cursos_by_region/" + id_region + "/" + tipo_unidad
        , method: "post"
        , error: function () {
            console.warn("No se pudo realizar la conexión");
        }
    }).done(function (response) {
        $('#curso').empty()
        $('#curso').append('<option value="">Seleccionar...</option>');
        var opts = $.parseJSON(response);
        // Use jQuery's each to iterate over the opts value
        $.each(opts, function (i, d) {
            $('#curso').append('<option value="' + d.id_curso + '">' + d.nombre + '</option>');
        });
    });
}
