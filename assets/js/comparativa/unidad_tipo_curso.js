$(function () {
    $('.unidad_texto').keyup(function () {
        search_unidad($(this));
    });

    $('#form_comparativa_unidad').submit(function (event) {
        event.preventDefault();
        if (valida_filtros('tipo_curso')) {
            $.ajax({
                url: $(this).attr('action')
                , method: "post"
                , data: $(this).serialize()
                , error: function () {
                    console.warn("No se pudo realizar la conexión");
                    ocultar_loader();
                }
                , beforeSend: function (xhr) {
                    $('#area_graph').html('');
                    mostrar_loader();
                    $('#area_reportes').css('display', 'none');
                }
            }).done(function (response) {
                var reportes = [1, 2, 3, 5];
                var datos = JSON.parse(response);
                for (i = 0; i < reportes.length; i++) {
                    var datos_g = procesa_datos(datos[i]);
                    console.log(datos_g);
                    var periodo = 2016;
                    var titulo_grafica = "Comparativa de unidades en " + periodo;
                    var texto = "";
                    var id_reporte = reportes[i];
                    var colores = ['#0090b9'];
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
                            texto = "Porcentaje de eficiencia terminal modificada";
                            break;
                        case 5:
                        case "5":
                            colores = ['#e53935'];
                            texto = "Número de alumnos no aprobados ";
                            break;
                    }
                    var extra = '';
                    graficar(i, datos_g, titulo_grafica, texto, periodo, extra, colores);
                }
                ocultar_loader();
                $('#area_reportes').css('display', 'block');
            });
        } else {
            alert('Debe seleccionar los filtros, antes de realizar una comparación');
        }
    });
});

function procesa_datos(datos) {
    var salida = [];
    salida[0] = [datos.unidad1.unidad, datos.unidad1.cantidad];
    salida[1] = [datos.unidad2.unidad, datos.unidad2.cantidad];
    return salida;
}

function graficar(id, datos, titulo, texto, year, extra, colores) {
    Highcharts.chart('area_graph' + id, {
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
            allowDecimals: false,
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