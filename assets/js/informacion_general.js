$( document ).ready(function() {
    //$('[data-toggle="tooltip"]').tooltip(); //Llamada a tooltip
    Highcharts.setOptions({
        lang: {
            contextButtonTitle: "Menú contextual",
            downloadJPEG: "Descargar imagen JPEG",
            downloadPDF: "Descargar documento PDF",
            downloadPNG: "Descargar imagen PNG",
            downloadSVG: "Descargar imagen en vectores SVG",
            drillUpText: "Regresar a {series.name}",
            loading: "Cargando...",
            months: [ "Enero" , "Febrero" , "Marzo" , "Abril" , "Mayo" , "Junio" , "Julio" , "Agosto" , "Septiembre" , "Octubre" , "Noviembre" , "Diciembre"],
            noData: "No hay datos que mostrar",
            printChart: "Imprimir gráfica",
            resetZoom: "Restablecer zoom",
            resetZoomTitle: "Restablecer zoom nivel 1:1",
            shortMonths: [ "Ene" , "Feb" , "Mar" , "Abr" , "May" , "Jun" , "Jul" , "Ago" , "Sep" , "Oct" , "Nov" , "Dic"],
            weekdays: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"]
        },
        credits: {
            enabled: false
        }
    });

    $("#filtros_capa").on("hide.bs.collapse", function(){
        $("#filtros_capa_header>i").addClass('fa-arrow-right');
        $("#filtros_capa_header>i").removeClass('fa-arrow-down');
    });
    $("#filtros_capa").on("show.bs.collapse", function(){
        //$("#filtros_capa_header>div").html('keyword_arrow_left');
        $("#filtros_capa_header>i").addClass('fa-arrow-down');
        $("#filtros_capa_header>i").removeClass('fa-arrow-right');
    });
});

function limpiar_capas() {
    $('#unidad_capa').html(''); 
    $('#comparativa_chrt').html(''); 
    $('#comparativa_chrt2').html('');
}
/**
 *	Método que muestra una imagen (gif animado) que indica que algo esta cargando
 *	@return	string	Contenedor e imagen del cargador.
 */
function calcular_totales_generales(path) {
    $.ajax({
        url: path,
        method: 'POST',
        dataType: 'json',
        beforeSend: function (xhr) {
            mostrar_loader();
        }
    })
    .done(function (response) {
        if(typeof(response.total) != "undefined"){
            $('#total_alumnos_inscritos').html(response.total.cantidad_alumnos_inscritos);
            $('#total_alumnos_aprobados').html(response.total.cantidad_alumnos_certificados);
            $('#total_alumnos_no_aprobados').html(response.total.cantidad_no_aprobados);
            $('#total_alumnos_no_acceso').html(response.total.cantidad_no_accesos);
            $('#total_eficiencia_terminal').html(calcular_eficiencia_terminal(response.total.cantidad_alumnos_inscritos, response.total.cantidad_alumnos_certificados, response.total.cantidad_no_accesos));
        }
    })
    .fail(function (jqXHR, textStatus) {
        //$(elemento_resultado).html("Ocurrió un error durante el proceso, inténtelo más tarde.");
        ocultar_loader();
    })
    .always(function () {
        ocultar_loader();
    });
}

function obtener_categoria_serie(datos){
    var categorias = [];
    var series_datos = [];
    var inscritos = [];
    var certificados = [];
    var no_acceso = [];
    var no_aprobado = [];
    jQuery.each( datos, function( i, val ) {
        categorias.push(i);
        inscritos.push(val.cantidad_alumnos_inscritos);
        certificados.push(val.cantidad_alumnos_certificados);
        no_acceso.push(val.cantidad_no_accesos);
        no_aprobado.push(val.cantidad_alumnos_inscritos-val.cantidad_alumnos_certificados-val.cantidad_no_accesos);
    });
    series_datos = [{
            name: 'Inscritos',
            data: inscritos,
            stack: 'inscritos'
        }, {
            name: 'Aprobados',
            data: certificados,
            stack: 'aprobados'
        }, {
            name: 'Nunca entraron',
            data: no_acceso,
            stack: 'no_aprobado'
        }, {
            name: 'No aprobados',
            data: no_aprobado,
            stack: 'no_aprobado'
        }];
    return resultado = {'categorias':categorias, 'series':series_datos};
}

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
            $('#total_alumnos_inscritos').html(response.total.cantidad_alumnos_inscritos);
            $('#total_alumnos_aprobados').html(response.total.cantidad_alumnos_certificados);
            $('#total_alumnos_no_aprobados').html(response.total.cantidad_no_aprobados);
            $('#total_alumnos_no_acceso').html(response.total.cantidad_no_accesos);
            $('#total_eficiencia_terminal').html(calcular_eficiencia_terminal(response.total.cantidad_alumnos_inscritos, response.total.cantidad_alumnos_certificados, response.total.cantidad_no_accesos));
        }
        /////////Perfiles
        /*var perfiles = [];
        var series_datos = [];
        var inscritos = [];
        var certificados = [];
        var no_acceso = [];
        var no_aprobado = [];
        jQuery.each( response.perfil, function( i, val ) {
            perfiles.push(i);
            inscritos.push(val.cantidad_alumnos_inscritos);
            certificados.push(val.cantidad_alumnos_certificados);
            no_acceso.push(val.cantidad_no_accesos);
            no_aprobado.push(val.cantidad_alumnos_inscritos-val.cantidad_alumnos_certificados-val.cantidad_no_accesos);
        });
        series_datos = [{
                name: 'Inscritos',
                data: inscritos,
                stack: 'inscritos'
            }, {
                name: 'Aprobados',
                data: certificados,
                stack: 'aprobados'
            }, {
                name: 'No acceso',
                data: no_acceso,
                stack: 'no_aprobado'
            }, {
                name: 'No aprobado',
                data: no_aprobado,
                stack: 'no_aprobado'
            }];*/
        var perfil = obtener_categoria_serie(response.perfil);
        crear_grafica_stacked_grouped('container_perfil', 'Por perfil', perfil.categorias, 'Número de alumnos', perfil.series);
        ////////Tipos de curso
        /*var tipos_curso = [];
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
            }];*/
        var tipos_curso = obtener_categoria_serie(response.tipo_curso);
        crear_grafica_stacked('container_tipo_curso', 'Por tipo de curso', tipos_curso.categorias, 'Número de alumnos', tipos_curso.series);
        ////////Región
        /*var region = [];
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
            }];*/
        var region = obtener_categoria_serie(response.region);
        crear_grafica_stacked('container_region', 'Por región', region.categorias, 'Número de alumnos', region.series);
        ////////Delegación
        /*var delegacion = [];
        var series_datos = [];
        var inscritos = [];
        var certificados = [];
        jQuery.each( response.delegacion, function( i, val ) {
            delegacion.push(i);
            inscritos.push(val.cantidad_alumnos_inscritos);
            certificados.push(val.cantidad_alumnos_certificados);
        });
        series_datos = [{
                name: 'Aprobados',
                data: certificados
            }, {
                name: 'Inscritos',
                data: inscritos
            }];*/
        var delegacion = obtener_categoria_serie(response.delegacion);
        crear_grafica_stacked('container_delegacion', 'Por delegación', delegacion.categorias, 'Número de alumnos', delegacion.series);
        ////////UMAE
        /*var umae = [];
        var series_datos = [];
        var inscritos = [];
        var certificados = [];
        jQuery.each( response.umae, function( i, val ) {
            umae.push(i);
            inscritos.push(val.cantidad_alumnos_inscritos);
            certificados.push(val.cantidad_alumnos_certificados);
        });
        series_datos = [{
                name: 'Aprobados',
                data: certificados
            }, {
                name: 'Inscritos',
                data: inscritos
            }];*/
        var umae = obtener_categoria_serie(response.umae);
        crear_grafica_stacked('container_umae', 'Por UMAE', umae.categorias, 'Número de alumnos', umae.series);
        ////////Periodo
        /*var periodo = [];
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
            }];*/
        var periodo = obtener_categoria_serie(response.periodo);
        crear_grafica_stacked('container_periodo', 'Por periodo', periodo.categorias, 'Número de alumnos', periodo.series);
        ////////Nivel de atención
        var nivel_atencion = obtener_categoria_serie(response.nivel_atencion);
        crear_grafica_stacked('container_nivel_atencion', 'Por nivel de atención', nivel_atencion.categorias, 'Número de alumnos', nivel_atencion.series);
    })
    .fail(function (jqXHR, textStatus) {
        $(elemento_resultado).html("Ocurrió un error durante el proceso, inténtelo más tarde.");
        ocultar_loader();
    })
    .always(function () {
        ocultar_loader();
    });
}

function obtener_categoria_serie_unidad(datos){
    var categorias = [];
    var series_datos = [];
    var inscritos = [];
    var certificados = [];
    var no_acceso = [];
    var no_aprobado = [];
    jQuery.each( datos, function( i, val ) {
        categorias.push(i);
        inscritos.push(val.cantidad_alumnos_inscritos);
        certificados.push(val.cantidad_alumnos_certificados);
        no_acceso.push(val.cantidad_no_accesos);
        no_aprobado.push(val.cantidad_alumnos_inscritos-val.cantidad_alumnos_certificados-val.cantidad_no_accesos);
    });
    series_datos = [{
            name: 'Inscritos',
            data: inscritos,
            stack: 'inscritos'
        }, {
            name: 'Aprobados',
            data: certificados,
            stack: 'aprobados'
        }, {
            name: 'Nunca entraron',
            data: no_acceso,
            stack: 'no_aprobado'
        }, {
            name: 'No aprobados',
            data: no_aprobado,
            stack: 'no_aprobado'
        }];
    return resultado = {'categorias':categorias, 'series':series_datos};
    /*var categorias = [];
    var series_datos = [];
    var inscritos = [];
    var certificados = [];
    var no_acceso = [];
    var no_aprobado = [];
    jQuery.each( datos, function( i, val ) {
        categorias.push(i);
        jQuery.each( val, function( i2, val2 ) {
            var t = [];
            var tt = '';
            jQuery.each( val2, function( i3, val3 ) {
                tt = i3;
                t.push(val3);
            });
            var tmp = {name: i2, data: t, stack: i};
            series_datos.push(tmp);
        });
    });
    return resultado = {'categorias':categorias, 'series':series_datos};*/
}

function calcular_totales_unidad(path, form_recurso) {
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
        if($('#tipo_grafica').val()=='perfil'){
            var perfil = obtener_categoria_serie_unidad(response.perfil);
            crear_grafica_stacked_grouped('comparativa_chrt', 'Por perfil', perfil.categorias, 'Número de alumnos', perfil.series);
            $('#comparativa_chrt2').html('');
        }
        if($('#tipo_grafica').val()=='tipo_curso'){
            var tipos_curso = obtener_categoria_serie_unidad(response.tipo_curso);
            crear_grafica_stacked('comparativa_chrt2', 'Por tipo de curso', tipos_curso.categorias, 'Número de alumnos', tipos_curso.series);
            $('#comparativa_chrt').html('');
        }
    })
    .fail(function (jqXHR, textStatus) {
        //$(elemento_resultado).html("Ocurrió un error durante el proceso, inténtelo más tarde.");
        console.log(jqXHR);
        console.log(textStatus);
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
            if(response.total == 0){
                $('#total_alumnos_inscritos').html('-');
                $('#total_alumnos_aprobados').html('-');
                $('#total_alumnos_no_aprobados').html('-');
                $('#total_alumnos_no_acceso').html('-');
                $('#total_eficiencia_terminal').html('-');
                $("#div_resultado").hide();
                $("#tabla_tipo_curso").html('');
                $("#tabla_perfil").html('');
                $('#container_perfil').html("<div class='alert alert-info text-center'><h5>No existen datos relacionados con los filtros seleccionados. <br>Realice una nueva selección.</h5></div>");
            } else {
                $('#total_alumnos_inscritos').html(response.total.cantidad_alumnos_inscritos);
                $('#total_alumnos_aprobados').html(response.total.cantidad_alumnos_certificados);
                $('#total_alumnos_no_aprobados').html(response.total.cantidad_no_aprobados);
                $('#total_alumnos_no_acceso').html(response.total.cantidad_no_accesos);
                $('#total_eficiencia_terminal').html(calcular_eficiencia_terminal(response.total.cantidad_alumnos_inscritos, response.total.cantidad_alumnos_certificados, response.total.cantidad_no_accesos));
                $("#div_resultado").show();
                $("#tabla_tipo_curso").html(response.tabla_tipo_curso);
                $("#tabla_perfil").html(response.tabla_perfil);
                //crear_grafica_area('container_perfil', '', periodos, 'Número de alumnos', series_datos);
                var periodo = obtener_categoria_serie(response.periodo);
                crear_grafica_stacked('container_perfil', '', periodo.categorias, 'Número de alumnos', periodo.series);
            }
            $(".collapse_element").collapse("hide");
        }
    })
    .fail(function (jqXHR, textStatus) {
        $(elemento_resultado).html("Ocurrió un error durante el proceso, inténtelo más tarde.");
        ocultar_loader();
    })
    .always(function () {
        ocultar_loader();
    });
}

function limpiar_filtros_listados(){
    var perfil_tree = $('#perfil_tree').fancytree('getTree');
    perfil_tree.reload(SOURCE);
    var tipo_curso_tree = $('#tipo_curso_tree').fancytree('getTree');
    tipo_curso_tree.reload(SOURCE2);

    jQuery.each( ['perfil', 'tipo_curso'], function( i, destino ) {
        //////////////////////////////////////////////////////////////////
        var tree = $('#'+destino+'_tree').fancytree('getTree');
        // Get a list of all selected nodes, and convert to a key array:
        var selKeys = $.map(tree.getSelectedNodes(), function(node){
            return node.key;
        });
        $('#'+destino+'_seleccion').val(selKeys.join(","));

        // Get a list of all selected TOP nodes
        var selRootNodes = tree.getSelectedNodes(true);
        // ... and convert to a key array:
        var selRootKeys = $.map(selRootNodes, function(node){
            return node.key;
        });
        $('#'+destino+'_seleccion_rootkey').val(selRootKeys.join(","));
        $('#'+destino+'_seleccion_node').val(selRootNodes.join(","));
        //////////////////////////////////////////////////////////////////
    });
    $("#temporal_tipo_busqueda").val('');
    setTimeout(function() {   //calls click event after a certain time
       buscar_perfil(site_url+'/informacion_general/buscar_perfil', '#form_busqueda');
    }, 500);
}

function mostrar_tipo_grafica(elemento){
    $(elemento+" > option").each(function() {
        //alert(this.text + ' - ' + this.value+' - '+this.selected);
        if($('#capa_'+this.value).length>0){
            $('#'+this.value).val('');
            if(this.selected==true){
                $('#capa_'+this.value).show();
                $('#container_'+this.value).show();
                calcular_totales(site_url+'/informacion_general/calcular_totales', '#form_busqueda');
            } else {
                $('#capa_'+this.value).hide();
                $('#container_'+this.value).hide();
            }
        }
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
        colors: ['#0090b9','#43A886','#FC6220','#EF5350','#FCB220'],
        xAxis: {
            categories: categorias
        },
        yAxis: {
            min: 0,
            title: {
                text: texto_y
            },
            stackLabels: {
                //enabled: false
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
                    //enabled: false,
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
        colors: ['#0090b9','#43A886','#EF5350','#FC6220','#FCB220'],
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

function crear_grafica_stacked_grouped(elemento, titulo, categorias, texto_y, series_datos){
    Highcharts.chart(elemento, {
        chart: {
            type: 'column'
        },
        title: {
            text: titulo
        },
        colors: ['#0090b9','#43A886','#EF5350','#FC6220','#FCB220'],
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
            formatter: function () {
                return '<b>' + this.x + '</b><br/>' +
                    this.series.name + ': ' + this.y + '<br/>' +
                    'Total: ' + this.point.stackTotal;
            }
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