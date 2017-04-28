<script src="<?php echo base_url(); ?>assets/third-party/highcharts/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/modules/exporting.js"></script>
<?php
echo js('comparativa/umae.js');
echo form_open('comparativa/umae', array('id' => 'form_comparativa'));
?>
<div class="row form-group">
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">Tipo de comparativa:</span>
            <?php
            echo $this->form_complete->create_element(
                    array('id' => 'comparativa',
                        'type' => 'dropdown',
                        'first' => array('' => 'Seleccione...'),
                        'options' => $comparativas,
                        'attributes' => array(
                            'class' => 'form-control  form-control input-sm',
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'top',
                            'title' => 'Tipo de comparativa',
                            'onchange' => 'cmbox_comparativa()')
                    )
            );
            ?>
        </div>
    </div>
</div>
<?php
echo form_close();
?>
<div id="area_comparativa"></div>

<div class="row">
    <div id="area_graph"></div>
</div>     