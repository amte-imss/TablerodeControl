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
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/data.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/modules/exporting.js"></script>

<?php echo js("chart_options.js"); ?>
<script type="text/javascript">
    $(document).ready(function () {
        //chart
        $('#area_reportes').css('display', 'block');
        chart("area_graph0", "table_inscritos", "", "<?php echo $titulo1; ?>", ['#0095bc']);        
        chart("area_graph1", "table_aprobados", "", "<?php echo $titulo2; ?>", ['#98c56e']);        
        chart("area_graph2", "table_etm", "", "<?php echo $titulo3; ?>", ['#f3b510']);        
        chart("area_graph3", "table_no_aprobados", "", "<?php echo $titulo4; ?>", ['#f05f50']);        
    });
</script>