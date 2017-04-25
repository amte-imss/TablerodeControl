<script src="<?php echo base_url(); ?>assets/third-party/highcharts/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/modules/exporting.js"></script>
<?php
echo js('ranking/index.js');
echo form_open('ranking/get_data/', array('id' => 'form_ranking'));
?>
<input type="hidden" name="umae" value="0">
<div class="row form-group">
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">Año:</span>
            <?php
            echo $this->form_complete->create_element(
                    array('id' => 'periodo',
                        'type' => 'dropdown',
                        'first'=>array(''=>'Seleccione...'),
                        'options' => $periodos, 
                        'attributes' => array(
                            'class' => 'form-control  form-control input-sm',
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'top',
                            'title' => 'Region', 
                            'onchange' => 'render_graph()')
                    )
            );
            ?>
        </div>
        <?php echo form_error_format('periodo'); ?>
    </div>
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">Programa:</span>
            <?php
            echo $this->form_complete->create_element(
                    array('id' => 'programa',
                        'type' => 'dropdown',
                        'first'=>array(''=>'Seleccione...'),
                        'options' => $programas, 
                        'attributes' => array(
                            'class' => 'form-control  form-control input-sm',
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'top',
                            'title' => 'Programa', 
                            'onchange' => 'render_graph()')
                    )
            );
            ?>
        </div>
        <?php echo form_error_format('programa'); ?>
    </div>
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">Tipo:</span>
            <?php
            echo $this->form_complete->create_element(
                    array('id' => 'tipo',
                        'type' => 'dropdown',
                        'first'=>array(''=>'Seleccione...'),
                        'options' => $graficas, 
                        'attributes' => array(
                            'class' => 'form-control  form-control input-sm',
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'top',
                            'title' => 'Tipo', 
                            'onchange' => 'render_graph()')
                    )
            );
            ?>
        </div>
        <?php echo form_error_format('tipo'); ?>
    </div>
</div>

<?php echo form_close(); ?>

<div class="row">
    <div id="area_graph"></div>
</div>     