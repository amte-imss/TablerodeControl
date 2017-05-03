$(function () {
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
            var titulo_grafica = "Comparativa de UMAE en el " + periodo;
            var texto = "";
            var id_reporte = document.getElementById('reporte').value;
            switch (id_reporte) {
                case 1:
                case "1":
                    texto = "Cantidad de alumnos inscritos ";
                    break;
                case 2:
                case "2":
                    texto = "Cantidad de alumnos aprobados ";
                    break;
                case 3:
                case "3":
                    texto = "Porcentaje de alumnos inscritos ";
                    break;
                case 5:
                case "5":
                    texto = "Cantidad de alumnos no aprobados ";
                    break;
            }
            var extra = '';
            switch (grafica) {
                case 1:
                    break;
            }
            graficar(datos, titulo_grafica, texto, periodo, extra);
            ocultar_loader();
        });
    });
});

function procesa_datos(datos) {
    var salida = [];
    salida[0] = [datos.unidad1.unidad, datos.unidad1.cantidad];
    salida[1] = [datos.unidad2.unidad, datos.unidad2.cantidad];
    return salida;
}

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

function graficar(datos, titulo, texto, year, extra) {
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
            min: 0,
            title: {
                text: texto
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: texto + ' en' + year + ' : <b>{point.y}' + extra + '</b>'
        },
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