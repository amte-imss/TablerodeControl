var cores_colores = ['#98c56e', '#f3b510', '#f05f50', '#0095bc'];
var cores_textos = ['Decisiones basadas en información', '¿Qué está pasando en materia educativa?', 'Nuestra visión, enfocarnos en los resultados', 'Claridad y significado en los datos'];
var cores_cantidad_bloques = 6;
var cores_time_ms = 5000;
var cores_index_banner = 0;

$(function () {    
    cores_render_points();
    cores_banner();
});

function cores_banner(){
    if(cores_index_banner>cores_textos.length){
        cores_index_banner = 0;
    }
    $('#cores-banner').fadeOut( "slow" );
    setTimeout(function (){
        $('#cores-banner').text(cores_textos[cores_index_banner++]).fadeIn('slow');
    }, 1000);            
    setTimeout(cores_banner, cores_time_ms);
}

function cores_render_points() {
    $('#cores-area-animation').html('');
    console.log('generando animacion');
    for (i = 0; i < cores_cantidad_bloques; i++) {
        for (j = 0; j < cores_colores.length; j++) {
            var size_limite = $('.cores-background').height() > $('#cores-area-animation').width() ? $('#cores-area-animation').width() : $('.cores-background').height();
            //var size_limite = $('.cores-background').height() > $('#cores-area-principal').width() ? $('#cores-area-principal').width() : $('.cores-background').height();
            var size = cores_get_size((size_limite * .75), i);
            var centro_h = ($('.cores-background').height() / 2) - (size / 2);
            var centro_w = ($('#cores-area-animation').width() / 2) - (size / 2);
            //var centro_w = ($('#cores-area-principal').width() / 2) - (size / 2);
            var area1 = $('<div>')
                    .css({width: size, height: size, top: centro_h, left: centro_w, position: 'absolute'});                       
            cores_agrega_punto(area1, size, j);
            $('#cores-area-animation').append(area1);
        }
    }
}

function cores_agrega_punto(area1, size, j) {
    var css = cores_get_css(size, j);
    var circle = $('<div>')
            .css(css);
    area1.append(circle);
    area1.addClass('cores-orbit');
}

function cores_get_size(max_size, index) {
    var tmp = Math.floor(Math.random() * max_size * (index + 1));
    while (tmp > max_size) {
        tmp = Math.floor(Math.random() * max_size * (index + 1));
    }
    return tmp;
}

function cores_get_css(size, index) {
    var size_circle = Math.floor(Math.random() * 15);
    var pos = Math.floor(Math.random() * (size));
    var a = {};
    switch (index) {
        case 0:
            a = {left: pos, position: 'absolute', width: size_circle, height: size_circle, background: cores_colores[index], 'border-radius': '50%'};
            break;
        case 1:
            a = {right: pos, position: 'relative', width: size_circle, height: size_circle, background: cores_colores[index], 'border-radius': '50%', float: 'right'};
            break;
        case 2:
            a = {left: pos, position: 'absolute', width: size_circle, height: size_circle, background: cores_colores[index], 'border-radius': '50%', bottom: '0px'};
            break;
        case 3:
            a = {right: pos, position: 'relative', width: size_circle, height: size_circle, background: cores_colores[index], 'border-radius': '50%', top: '100%', float: 'right'}
            break;
    }
    return a;
}