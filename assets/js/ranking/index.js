$(function () {
    $('#form_ranking').submit(function (event) {
        event.preventDefault();
        $('#alert-ranking').css('display', 'none');
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
            console.log(datos);
            if (datos.length > 0) {
                var grafica = document.getElementById('tipo').value;
                if (grafica == "" || grafica == 1) {
                    aprobados(datos);
                } else {
                    eficiencia_terminal(datos);
                }
            }else{
                $('#alert-ranking').css('display', 'block');
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
    var periodo = $("#periodo option:selected").text();
    var colores = ['#0090b9'];
    var titulo_grafica = "Ranking de alumnos aprobados del programa " + programa + " en " + periodo;
    if (id_programa == "") {
        titulo_grafica = "Ranking de alumnos aprobados " + " en " + periodo;
    }
    var texto = "Número de alumnos aprobados ";
    var extra = '';
    graficar_ranking(datos, titulo_grafica, texto, periodo, extra, colores);
}

function eficiencia_terminal(datos) {
    var id_programa = document.getElementById('programa').value;
    var programa = "";
    if (id_programa != "" && datos.length > 0) {
        programa = datos[0].programa;
    }

    datos = procesa_datos_etm(datos);
    var periodo = 2016;
    var colores = ['#FCB220'];
    var titulo_grafica = "Ranking por eficiencia terminal modificada del programa " + programa + " en " + periodo;
    if (id_programa == "") {
        titulo_grafica = "Ranking por eficiencia terminal modificada  " + " en " + periodo;
    }
    var texto = "Porcentaje de eficiencia terminal ";
    var extra = '';
    graficar_ranking(datos, titulo_grafica, texto, periodo, extra, colores);
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
        if (datos[i].inscritos != datos[i].no_acceso) {
            var eficiencia_terminal = (datos[i].aprobados / (datos[i].inscritos - datos[i].no_acceso)) * 100;
            eficiencia_terminal = parseInt(eficiencia_terminal);
            // console.log(datos[i].nombre + ' ' + datos[i].inscritos + ' ' + datos[i].aprobados + ' '+ datos[i].no_acceso + ' ' + calcular_eficiencia_terminal(datos[i].inscritos, datos[i].aprobados,datos[i].no_acceso));
            salida[i] = [datos[i].nombre, eficiencia_terminal];
        } else {
            salida[i] = [datos[i].nombre, 0];
        }
    }
    return salida.sort(function (a, b) {
        return b[1] - a[1]
    });
    ;
}

function render_graph() {
    if (valida_filtros()) {
        $('#form_ranking').submit();
    }
}

function valida_filtros() {
    var campos = ['periodo'];
    var valido = true;
    for (i = 0; i < campos.length; i++) {
        if (document.getElementById(campos[i]) != null) {
            var value = document.getElementById(campos[i]).value;
            if (value == null || value == "") {
                valido = false;
                console.log('elemento no encontrado: ' + campos[i]);
            }
        } else {
            console.log('elemento no encontrado: ' + campos[i]);
            valido = false;
        }
    }
    return valido;
}

function    graficar_ranking(datos, titulo, texto, year, extra, colores) {
    Highcharts.chart('area_graph', {
        chart: {
            type: 'column'
        },
        colors: colores,
        title: {
            text: titulo
        },
        xAxis: {
            type: 'category',
            labels: {
                rotation: -90,
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
            },
            visible: false
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: texto + ' en ' + year + ' : <b>{point.y} ' + extra + '</b>'
        },
        series: [{
                name: texto,
                data: datos
                , dataLabels: {
                    enabled: true,
                    rotation: -90,
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

function graficar_ranking_vertical(datos, titulo, texto, year, extra) {
    Highcharts.chart('area_graph_vertical', {
        chart: {
            type: 'bar'
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
            pointFormat: texto + ' en ' + year + ' : <b>{point.y:.1f} ' + extra + '</b>'
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