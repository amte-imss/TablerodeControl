$(function () {
    $('#form_ranking').submit(function (event) {
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
            var grafica = document.getElementById('tipo').value;
            if (grafica == "" || grafica == 1) {
                aprobados(datos);
            } else {
                eficiencia_terminal(datos);
            }
            ocultar_loader();
        });
    });
});

function aprobados(datos) {
    var id_programa = document.getElementById('programa').value;
    var programa = "";
    if (id_programa != "" && datos.length > 0) {
        programa = datos[0].programa;
    }
    datos = procesa_datos(datos);
    var periodo = 2016;
    var titulo_grafica = "Ranking delegacional de alumnos aprobados del programa " + programa + " en el " + periodo;
    if (id_programa == "") {
        titulo_grafica = "Ranking delegacional de alumnos aprobados " + " en el " + periodo;
    }
    var texto = "Alumnos aprobados";
    var extra = '';
    graficar_ranking(datos, titulo_grafica, texto, periodo, extra);
}

function eficiencia_terminal(datos) {
    var id_programa = document.getElementById('programa').value;
    var programa = "";
    if (id_programa != "" && datos.length > 0) {
        programa = datos[0].programa;
    }
    console.log(datos);
    datos = procesa_datos_etm(datos);   
    var periodo = 2016;
    var titulo_grafica = "Ranking delegacional por eficiencia terminal del programa " + programa + " en el " + periodo;
    if (id_programa == "") {
        titulo_grafica = "Ranking delegacional por eficiencia terminal " + " en el " + periodo;
    }
    var texto = "Porcentaje de eficiencia terminal";
    var extra = '';
    graficar_ranking(datos, titulo_grafica, texto, periodo, extra);
}

function procesa_datos(datos) {
    var salida = [];
    for (i = 0; i < datos.length; i++) {
        salida[i] = [datos[i].nombre, datos[i].cantidad];
    }
    return salida;
}

function procesa_datos_etm(datos) {
    var salida = [];
    for (i = 0; i < datos.length; i++) {
        if (datos[i].aprobados != datos[i].no_acceso) {
            salida[i] = [datos[i].nombre, calcular_eficiencia_terminal(parseFloat(datos[i].inscritos), parseFloat(datos[i].aprobados), parseFloat(datos[i].no_acceso))];
        } else {
            salida[i] = [datos[i].nombre, 0];
        }
    }
    return salida;
}

function render_graph() {
    $('#form_ranking').submit();
}

function    graficar_ranking(datos, titulo, texto, year, extra) {
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
                rotation: -45,
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
            pointFormat: texto + ' en' + year + ' : <b>{point.y:.1f}' + extra + '</b>'
        },
        series: [{
                name: texto,
                data: datos
                , dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    format: '{point.y:.1f}', // one decimal
                    y: 10, // 10 pixels down from the top
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            }]
    });
}