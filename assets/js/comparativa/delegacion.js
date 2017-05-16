function cmbox_comparativa() {
    var id_destino = document.getElementById('comparativa').value;
    if (id_destino == "") {
        $('#form_delegacion_perfil').css('display', 'none');
        $('#form_delegacion_tipo_curso').css('display', 'none');
        $('#area_reportes').css('display', 'none');
    } else {
        var destino = site_url + '/comparativa/delegacion_v2/';
        $.ajax({
            url: destino
            , method: "post"
            , data: {view: id_destino}
            , error: function () {
                console.warn("No se pudo realizar la conexión");
            }
            , beforeSend: function (xhr) {
                $('#area_comparativa').html('');
                mostrar_loader();
            }
        }).done(function (response) {
            $('#area_comparativa').html(response);
            $('#area_graph').html('');
            $('#area_reportes').css('display', 'none');
            ocultar_loader();
        });
    }
}
function valida_filtros(formulario) {
    var valido = false
    switch (formulario) {
        case 1:
        case "1":
            valido = valida_filtros_aux(['tipo_curso', 'delegacion1', 'delegacion2', 'periodo']);
            break;
        case 2:
        case "2":
            valido = valida_filtros_aux(['subperfil', 'delegacion1', 'delegacion2', 'periodo']);
            break;
    }
    return valido;
}

function valida_filtros_aux(campos) {
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

function cmbox_nivel() {
    var nivel = document.getElementById('nivel').value;
    if (nivel != null && nivel != "") {
        var datos = {nivel: nivel};
        if (document.getElementById('umae') && document.getElementById('umae').value == 1) {
            datos = {nivel: nivel, umae: 1};
        }
        $.ajax({
            url: site_url + "/buscador/get_tipo_unidad/"
            , method: "post"
            , data: datos
            , error: function () {
                console.warn("No se pudo realizar la conexión");
            }
            , beforeSend: function (xhr) {
                mostrar_loader();
            }
        }).done(function (response) {
            $('#tipo_unidad').empty()
            var opts = $.parseJSON(response);
            $('#tipo_unidad').append('<option value="">Seleccionar...</option>');
            // Use jQuery's each to iterate over the opts value
            $.each(opts, function (i, d) {
                $('#tipo_unidad').append('<option value="' + d.id_tipo_unidad + '">' + d.nombre + '</option>');
            });
            ocultar_loader();
        });
    }
}


function submit_delegacion(elemento) {
    if (valida_filtros(document.getElementById('tipo_comparativa').value)) {
        var datos = elemento.serialize();
        if (document.getElementById('umae') != null && document.getElementById('umae').value != "") {
            datos += '&umae=' + document.getElementById('umae').value;
        }
        $('.alert-comparativa').css('display', 'none');
        $.ajax({
            url: elemento.attr('action')
            , method: "post"
            , data: datos
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
                var colores = ['#0090b9'];
                switch (id_reporte) {
                    case 1:
                    case "1":
                        texto = "Número de alumnos inscritos ";
                        texto_t = "inscritos";
                        break;
                    case 2:
                    case "2":
                        colores = ['#43a886'];
                        texto = "Número de alumnos aprobados ";
                        texto_t = "aprobados";
                        break;
                    case 3:
                    case "3":
                        colores = ['#FCB220'];
                        texto = "Porcentaje de eficiencia terminal modificada ";
                        texto_t = "por eficiencia terminal modificada";
                        break;
                    case 5:
                    case "5":
                        colores = ['#e53935'];
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
}

function procesa_datos(datos, index) {
    var salida = [];
    if (datos.delegacion1.delegacion == "") {
        datos.delegacion1.delegacion = $("#delegacion1 option:selected").text();
    }
    if (datos.delegacion2.delegacion == "") {
        datos.delegacion2.delegacion = $("#delegacion2 option:selected").text();
    }
    salida[0] = [datos.delegacion1.delegacion, datos.delegacion1.cantidad];
    salida[1] = [datos.delegacion2.delegacion, datos.delegacion2.cantidad];
    if (salida[0][1] == 0 && salida[1][1] == 0) {
        $('#area_graph'+index).css('display', 'none');
        $('#alert-comparativa' + index).css('display', 'block');
    }else{
        $('#area_graph'+index).css('display', 'block');
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