<?php

/* 
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
$titulo1 = 'Número de alumnos inscritos ';
$titulo2 =     'Número de alumnos aprobados ';
$titulo3 =     'Porcentaje de eficiencia terminal modificada ';
$titulo4 =     'Número de alumnos no aprobados ';
?>

<?php echo js("chart_options.js"); ?>
<script type="text/javascript">
    var titulo1 = '<?php echo $titulo1; ?>' + get_descripcion_filtros();
    var titulo2 = '<?php echo $titulo1; ?>' + get_descripcion_filtros();
    var titulo3 = '<?php echo $titulo1; ?>' + get_descripcion_filtros();
    var titulo4 = '<?php echo $titulo1; ?>' + get_descripcion_filtros();
    $(document).ready(function () {
        //chart
        $('#area_reportes').css('display', 'block');
        chart("area_graph0", "table_inscritos", titulo1, "<?php echo $titulo1; ?>", ['#0095bc']);        
        chart("area_graph1", "table_aprobados", titulo2, "<?php echo $titulo2; ?>", ['#98c56e']);        
        chart("area_graph2", "table_etm", titulo3, "<?php echo $titulo3; ?>", ['#f3b510']);        
        chart("area_graph3", "table_no_aprobados", titulo4, "<?php echo $titulo4; ?>", ['#f05f50']);        
    });
</script>