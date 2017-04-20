$( document ).ready(function() {
    //$('[data-toggle="tooltip"]').tooltip(); //Llamada a tooltip
});
/**
 *	Método que muestra una imagen (gif animado) que indica que algo esta cargando
 *	@return	string	Contenedor e imagen del cargador.
 */
function calcular_totales(path, form_recurso) {
    var dataSend = $(form_recurso).serialize();
    $.ajax({
        url: path,
        data: dataSend,
        method: 'POST',
        dataType: 'json',
        beforeSend: function (xhr) {
            mostrar_loader();
        }
    })
    .done(function (response) {
        if(typeof(response.total) != "undefined"){
            var eficiencia_terminal = response.total.cantidad_alumnos_inscritos/response.total.cantidad_alumnos_certificados;
            $('#total_alumnos_inscritos').html(response.total.cantidad_alumnos_inscritos);
            $('#total_alumnos_aprobados').html(response.total.cantidad_alumnos_certificados);
            $('#total_eficiencia_terminal').html(eficiencia_terminal.toFixed(3));
        }
        /////////Perfiles
        var perfiles = [];
        var series_datos = [];
        var inscritos = [];
        var certificados = [];
        jQuery.each( response.perfil, function( i, val ) {
            perfiles.push(i);
            inscritos.push(val.cantidad_alumnos_inscritos);
            certificados.push(val.cantidad_alumnos_certificados);
        });
        series_datos = [{
                name: 'Aprobados',
                data: certificados
            }, {
                name: 'Inscritos',
                data: inscritos
            }];
        crear_grafica_stacked('container_perfil', 'Por perfil', perfiles, 'Número de alumnos', series_datos);
        ////////Tipos de curso
        var tipos_curso = [];
        var series_datos = [];
        var inscritos = [];
        var certificados = [];
        jQuery.each( response.tipo_curso, function( i, val ) {
            tipos_curso.push(i);
            inscritos.push(val.cantidad_alumnos_inscritos);
            certificados.push(val.cantidad_alumnos_certificados);
        });
        series_datos = [{
                name: 'Aprobados',
                data: certificados
            }, {
                name: 'Inscritos',
                data: inscritos
            }];
        crear_grafica_stacked('container_tipo_curso', 'Por tipo de curso', tipos_curso, 'Número de alumnos', series_datos);
        ////////Región
        var region = [];
        var series_datos = [];
        var inscritos = [];
        var certificados = [];
        jQuery.each( response.region, function( i, val ) {
            region.push(i);
            inscritos.push(val.cantidad_alumnos_inscritos);
            certificados.push(val.cantidad_alumnos_certificados);
        });
        series_datos = [{
                name: 'Aprobados',
                data: certificados
            }, {
                name: 'Inscritos',
                data: inscritos
            }];
        crear_grafica_stacked('container_region', 'Por región', region, 'Número de alumnos', series_datos);
        ////////Periodo
        var periodo = [];
        var series_datos = [];
        var inscritos = [];
        var certificados = [];
        jQuery.each( response.periodo, function( i, val ) {
            //jQuery.each( val, function( inc, value ) {
                periodo.push(i);
                inscritos.push(val.cantidad_alumnos_inscritos);
                certificados.push(val.cantidad_alumnos_certificados);
            //});
        });
        series_datos = [{
                name: 'Aprobados',
                data: certificados
            }, {
                name: 'Inscritos',
                data: inscritos
            }];
        crear_grafica_stacked('container_periodo', 'Por periodo', periodo, 'Número de alumnos', series_datos);
    })
    .fail(function (jqXHR, textStatus) {
        $(elemento_resultado).html("Ocurrió un error durante el proceso, inténtelo más tarde.");
        ocultar_loader();
    })
    .always(function () {
        ocultar_loader();
    });
}

/**
 *  Método que muestra una imagen (gif animado) que indica que algo esta cargando
 *  @return string  Contenedor e imagen del cargador.
 */
function buscar_perfil(path, form_recurso) {
    var dataSend = $(form_recurso).serialize();
    $.ajax({
        url: path,
        data: dataSend,
        method: 'POST',
        dataType: 'json',
        beforeSend: function (xhr) {
            mostrar_loader();
        }
    })
    .done(function (response) {
        if(typeof(response.total) != "undefined"){
            var eficiencia_terminal = response.total.cantidad_alumnos_inscritos/response.total.cantidad_alumnos_certificados;
            $('#total_alumnos_inscritos').html(response.total.cantidad_alumnos_inscritos);
            $('#total_alumnos_aprobados').html(response.total.cantidad_alumnos_certificados);
            $('#total_eficiencia_terminal').html(eficiencia_terminal.toFixed(3));
            $("#div_resultado").show();
            $("#tabla_tipo_curso").html(response.tabla_tipo_curso);
            $("#tabla_perfil").html(response.tabla_perfil);
        }
        /////////Perfiles
        var periodos = [];
        var series_datos = [];
        var inscritos = [];
        var certificados = [];
        jQuery.each( response.periodo, function( i, val ) {
            periodos.push(i);
            inscritos.push(val.cantidad_alumnos_inscritos);
            certificados.push(val.cantidad_alumnos_certificados);
        });
        series_datos = [{
                name: 'Aprobados',
                data: certificados
            }, {
                name: 'Inscritos',
                data: inscritos
        }];
        crear_grafica_area('container_perfil', '', periodos, 'Número de alumnos', series_datos);
    })
    .fail(function (jqXHR, textStatus) {
        $(elemento_resultado).html("Ocurrió un error durante el proceso, inténtelo más tarde.");
        ocultar_loader();
    })
    .always(function () {
        ocultar_loader();
    });
}

function crear_grafica_stacked(elemento, titulo, categorias, texto_y, series_datos){
    Highcharts.chart(elemento, {
        chart: {
            type: 'column'
        },
        title: {
            text: titulo
        },
        xAxis: {
            categories: categorias
        },
        yAxis: {
            min: 0,
            title: {
                text: texto_y
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            }
        },
        legend: {
            align: 'right',
            x: -30,
            verticalAlign: 'top',
            y: 25,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            headerFormat: '<b>{point.x}</b><br/>',
            pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                }
            }
        },
        series: series_datos
    });
}

function crear_grafica_area(elemento, titulo, categorias, texto_y, series_datos){
    Highcharts.chart(elemento, {
        chart: {
            type: 'area'
        },
        title: {
            text: titulo
        },
        xAxis: {
            tickmarkPlacement: 'on',
            title: {
                enabled: false
            },
            categories: categorias
        },
        yAxis: {
            title: {
                text: texto_y
            },
            labels: {
                formatter: function () {
                    return this.value;
                }
            }
        },
        tooltip: {
            split: true,
            valueSuffix: ' alumnos'
        },
        plotOptions: {
            area: {
                stacking: 'normal',
                lineColor: '#666666',
                lineWidth: 1,
                marker: {
                    lineWidth: 1,
                    lineColor: '#666666'
                }
            }
        },
        series: series_datos
    });
}
