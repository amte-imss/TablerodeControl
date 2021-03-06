$(function () {
    console.log('activando paginacion');
    $("ul.pagination li a").click(function (event) {
        event.preventDefault();
        paginar_general($(this));
    });
});

function paginar_general(obj) {
    var num_page = obj.attr('data-ci-pagination-page') -1;
    var limit = document.getElementById('pagination_limit').value;
    if ($.isNumeric(num_page)) {
        document.getElementById('pagination_current_page').value = num_page;      
        var action = $('#form_paginacion').attr('action');
        $('#form_paginacion').attr('action', action + ((num_page) * limit));
        $('#form_paginacion').submit();
    }
}