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
            var datos = JSON.parse(response);
            datos = procesa_datos(datos);
            console.log(datos);
            var grafica = document.getElementById('reporte').value;
            var periodo = 2016;
            var titulo_grafica = "Comparativa de UMAE en " + periodo;
            var texto = "";
            var id_reporte = document.getElementById('reporte').value;
            colores = ['#999999'];
            switch (id_reporte) {
                case 1:
                case "1":
                    texto = "Número de alumnos inscritos";
                    break;
                case 2:
                case "2":
                    colores = ['#43A886'];
                    texto = "Número de alumnos aprobados";
                    break;
                case 3:
                case "3":
                    colores = ['#0090b9'];
                    texto = "Porcentaje de alumnos inscritos";
                    break;
                case 5:
                case "5":
                    colores = ['#EF5350'];
                    texto = "Número de alumnos no aprobados";
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