$(function () {
    $('.unidad_texto').keyup(function () {

        var index = $(this)[0].getAttribute('data-id');
        var keyword = document.getElementById('unidad' + index + '_texto').value;
        var tipo_unidad = document.getElementById('tipo_unidad').value;
        console.log('buscando:' + keyword);
        $.ajax({
            url: site_url + '/buscador/search_unidad_instituto'
            , method: "post"
            , timeout: 200
            , data: {keyword: keyword, tipo_unidad:tipo_unidad}
            , error: function () {
                console.warn("No se pudo realizar la conexión");
            }
        }).done(function (response) {
            if (index > 1) {
                response = '<li class="autocomplete_unidad" data-unidad-nombre="PROMEDIO" data-unidad-id="0" onclick="set_value_unidad(this)" >PROMEDIO</li>' + response;
            }
            $('#unidad' + index + '_autocomplete').css('display', 'block');
            $('#unidad' + index + '_autocomplete').html(response);
        });

    });
    $('#form_comparativa_umae').submit(function (event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action')
            , method: "post"
            , data: $(this).serialize()
            , error: function () {
                console.warn("No se pudo realizar la conexión");
            }
            , beforeSend: function (xhr) {
                $('#area_graph').html('');
                mostrar_loader();
            }
        }).done(function (response) {
            //console.log(response);
            var datos = JSON.parse(response);
            datos = procesa_datos(datos);
            console.log(datos);
            var grafica = document.getElementById('reporte').value;
            var periodo = 2016;
            var titulo_grafica = "Comparativa de unidades en " + periodo;
            var texto = "";
            var id_reporte = document.getElementById('reporte').value;
            colores = ['#0090b9'];
            switch (id_reporte) {
                case 1:
                case "1":
                    texto = "Número de alumnos inscritos ";
                    break;
                case 2:
                case "2":
                    colores = ['#43a886'];
                    texto = "Número de alumnos aprobados ";
                    break;
                case 3:
                case "3":
                    colores = ['#FCB220'];
                    texto = "Porcentaje de eficiencia terminal ";
                    break;
                case 5:
                case "5":
                    colores = ['#e53935'];
                    texto = "Número de alumnos no aprobados ";
                    break;
            }
            var extra = '';
            switch (grafica) {
                case 1:
                    break;
            }
            graficar(datos, titulo_grafica, texto, periodo, extra, colores);
            ocultar_loader();
        });
    });
});

function cmbox_perfil() {
    var subcategoria = document.getElementById('perfil').value;
    $.ajax({
        url: site_url + '/buscador/search_grupos_categorias'
        , method: "post"
        , data: {subcategoria: subcategoria}
        , error: function () {
            console.warn("No se pudo realizar la conexión");
        }
        , beforeSend: function (xhr) {
            mostrar_loader();
        }
    }).done(function (response) {
        $('#subperfil').empty()
        var opts = $.parseJSON(response);
        $('#subperfil').append('<option value="">Seleccionar...</option>');
        // Use jQuery's each to iterate over the opts value
        $.each(opts, function (i, d) {
            $('#subperfil').append('<option value="' + d.id_grupo_categoria + '">' + d.nombre + '</option>');
        });
        ocultar_loader();
    });
}

function set_value_unidad(item) {
    var id_unidad = item.getAttribute("data-unidad-id");
    var unidad = item.getAttribute("data-unidad-nombre");
    var index = item.parentElement.getAttribute('data-autocomplete-id');
    console.log(index);
    document.getElementById('unidad' + index).value = id_unidad;
    document.getElementById('unidad' + index + '_texto').value = unidad;
    $('#unidad' + index + '_autocomplete').css('display', 'none');
    $('#unidad' + index + '_autocomplete').html('');
}

function procesa_datos(datos) {
    var salida = [];
    salida[0] = [datos.unidad1.unidad, datos.unidad1.cantidad];
    salida[1] = [datos.unidad2.unidad, datos.unidad2.cantidad];
    return salida;
}


function graficar(datos, titulo, texto, year, extra, colores) {
    Highcharts.chart('area_graph', {
        chart: {
            type: 'column'
        },
        title: {
            text: titulo
        },
        xAxis: {
            type: 'category',
            labels: {
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            allowDecimals: false,
            min: 0,
            title: {
                text: texto
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: texto + ' en ' + year + ' : <b>{point.y} ' + extra + '</b>'
        },
        colors: colores,
        series: [{
                name: texto,
                data: datos
                , dataLabels: {
                    enabled: true,
                    color: '#FFFFFF',
                    align: 'right',
                    format: '{point.y}', // one decimal
                    y: 10, // 10 pixels down from the top
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            }]
    });
}