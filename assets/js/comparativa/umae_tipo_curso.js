$(function () {
    $('#form_comparativa_umae').submit(function (event) {
        event.preventDefault();
        $('.alert-comparativa').css('display', 'none');
        if (valida_filtros('tipo_curso')) {
            var agrupamiento = 0;
            if(document.getElementById('agrupamiento') != null){
                agrupamiento = document.getElementById('agrupamiento').value;
            }
            $.ajax({
                url: $(this).attr('action')
                , method: "post"
                , data: $(this).serialize() + '&agrupamiento=' + agrupamiento
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
                    var datos_g = procesa_datos(datos[i], i);
                    var periodo = $("#periodo option:selected").text();
                    var texto = "";
                    var texto_t = "";
                    var id_reporte = reportes[i];
                    var colores = ['#0095bc'];
                    switch (id_reporte) {
                        case 1:
                        case "1":
                            texto = "Número de alumnos inscritos ";
                            texto_t = "inscritos";
                            break;
                        case 2:
                        case "2":
                            colores = ['#98c56e'];
                            texto = "Número de alumnos aprobados ";
                            texto_t = "aprobados";
                            break;
                        case 3:
                        case "3":
                            colores = ['#f3b510'];
                            texto = "Porcentaje de eficiencia terminal modificada ";
                            texto_t = "por eficiencia terminal modificada";
                            break;
                        case 5:
                        case "5":
                            colores = ['#f05f50'];
                            texto = "Número de alumnos no aprobados ";
                            texto_t = "no aprobados";
                            break;
                    }
                    var titulo_grafica = "Comparativa de alumnos " + texto_t + " en " + periodo;
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

function procesa_datos(datos,index) {
    var salida = [];
    
    datos.unidad1.unidad = $("#unidad1 option:selected").text();    
    
    datos.unidad2.unidad = $("#unidad2 option:selected").text();
    
    salida[0] = [datos.unidad1.unidad, datos.unidad1.cantidad];
    salida[1] = [datos.unidad2.unidad, datos.unidad2.cantidad];
    if (salida[0][1] == 0 && salida[1][1] == 0) {
        $('#area_graph' + index).css('display', 'none');
        $('#alert-comparativa' + index).css('display', 'block');
    } else {
        $('#area_graph' + index).css('display', 'block');
    }
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
            allowDecimals: false,
            min: 0,
            title: {
                text: texto
            },
            visible: false
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