$(function () {
    $('.unidad_texto').keyup(function () {

        var index = $(this)[0].getAttribute('data-id');
        keyword = document.getElementById('unidad'+index+'_texto').value;
        console.log('buscando:' + keyword);
        $.ajax({
            url: site_url + '/buscador/search_unidad_instituto'
            , method: "post"
            , timeout: 200
            , data: {keyword: keyword}
            , error: function () {
                console.warn("No se pudo realizar la conexión");
            }
        }).done(function (response) {

            $('#unidad'+index+'_autocomplete').css('display', 'block');
            $('#unidad'+index+'_autocomplete').html(response);
        });

    });
    $('#form_comparativa_unidad').submit(function (event) {
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
            var datos = JSON.parse(response);
            datos = procesa_datos(datos);
            console.log(datos);
            var grafica = document.getElementById('reporte').value;
            var periodo = 2016;
            var titulo_grafica = "Comparativa de unidades en el " + periodo;
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