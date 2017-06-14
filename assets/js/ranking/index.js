$(function(){
    $('#form_ranking').submit(function(event){        
        if(!valida_filtros()){                    
            event.preventDefault();
            alert('Seleccione los campos requeridos');
        }
    });
});

function chart(id_chart, tabla, titulo, ytext, color) {    
    Highcharts.chart(id_chart, {
        data: {
            table: tabla
        },
        chart: {
            type: 'column'
        },
        colors: color,
        title: {
            text: titulo
        },
        legend: {
            enabled: false
        },
        yAxis: {
            allowDecimals: false,
            title: {
                text: ytext
            }
        }                
    });
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

