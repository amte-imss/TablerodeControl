function cmbox_comparativa() {
    var id_destino = document.getElementById('comparativa').value;
    var destino = site_url + '/comparativa/umae_tipo_curso';
    if(id_destino == 2){
        destino = site_url + '/comparativa/umae_perfil';
    }
    $.ajax({
        url: destino
        , method: "post"
        , error: function () {
            console.warn("No se pudo realizar la conexión");
        }
        , beforeSend: function (xhr) {
            $('#area_comparativa').html('');
            mostrar_loader();
        }
    }).done(function (response) {
        $('#area_comparativa').html(response);
        ocultar_loader();
    });
}
