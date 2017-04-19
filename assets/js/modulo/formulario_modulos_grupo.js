$('#form_modulos_grupo').submit(function (event) {
    event.preventDefault();
    var id_grupo = document.getElementById('grupo').value;
    $.ajax({
        url: site_url + "/modulo/get_modulos_grupo/" + id_grupo
        , method: "post"
        , data: $(this).serialize()
        , error: function () {
            console.warn("No se pudo realizar la conexión");
        }
    }).done(function (response) {
        $('#area_grupo').html(response);
    });
});
        