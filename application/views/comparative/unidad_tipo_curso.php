<?php
echo js('chart_options.js');
echo js('comparativa/unidad_tipo_curso.js');
echo form_open('comparativa/unidades_tipo_curso', array('id' => 'form_comparativa_unidad'));
?>
<div class="row form-group">
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">Reporte:</span>
            <?php
            echo $this->form_complete->create_element(
                    array('id' => 'reporte',
                        'type' => 'dropdown',
                        'first' => array('' => 'Seleccione...'),
                        'options' => $reportes,
                        'attributes' => array(
                            'class' => 'form-control  form-control input-sm',
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'top',
                            'title' => 'Region',
                            'onchange' => '')
                    )
            );
            ?>
        </div>
        <?php echo form_error_format('reporte'); ?>
    </div>
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">Tipo de curso:</span>
            <?php
            echo $this->form_complete->create_element(
                    array('id' => 'tipo_curso',
                        'type' => 'dropdown',
                        'first' => array('' => 'Seleccione...'),
                        'options' => $tipos_cursos,
                        'attributes' => array(
                            'class' => 'form-control  form-control input-sm',
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'top',
                            'title' => 'Tipo de curso',
                            'onchange' => '')
                    )
            );
            ?>
        </div>
        <?php echo form_error_format('tipo_curso'); ?>
    </div>    
</div>            
<div class="row form-group">
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">Unidad:</span>
            <input type="hidden" value="" name="unidad1" id="unidad1">
            <?php
            echo $this->form_complete->create_element(
                    array('id' => 'unidad1_texto',
                        'type' => 'text',
                        'attributes' => array(
                            'class' => 'form-control  form-control input-sm  unidad_texto',
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'top',
                            'data-id' => 1,
                            'autocomplete' => 'off', 
                            'title' => 'Unidad 1')
                    )
            );
            ?>
            <ul data-autocomplete-id="1" id="unidad1_autocomplete" style="display:none;"></ul>
        </div>
        <?php echo form_error_format('unidad1'); ?>
    </div>
    <div class="col-md-4">
        <div class="input-group input-group-sm">
             <span class="input-group-addon">comparar con :</span>
            <input type="hidden" value="" name="unidad2" id="unidad2">
            <?php
            echo $this->form_complete->create_element(
                    array('id' => 'unidad2_texto',
                        'type' => 'text',
                        'attributes' => array(
                            'class' => 'form-control  form-control input-sm  unidad_texto',
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'top',
                            'data-id' => 2, 
                            'autocomplete' => 'off', 
                            'title' => 'Unidad 2')
                    )
            );
            ?>
            <ul data-autocomplete-id="2" id="unidad2_autocomplete" style="display:none;"></ul>
        </div>
        <?php echo form_error_format('unidad2'); ?>
    </div>
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">Año:</span>
            <?php
            echo $this->form_complete->create_element(
                    array('id' => 'periodo',
                        'type' => 'dropdown',
                        'first' => array('' => 'Seleccione...'),
                        'options' => $periodos,
                        'attributes' => array(
                            'class' => 'form-control  form-control input-sm',
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'top',
                            'title' => 'Region',
                            'onchange' => '')
                    )
            );
            ?>
        </div>
        <?php echo form_error_format('periodo'); ?>
    </div>
</div>            
<div class="row form-group">    
    <div class="col-md-6">
        <div class="input-group input-group-sm">
            <input type="submit" name="submit" value="Comparar" class="btn btn-primary">
        </div>
    </div>
</div>
<?php echo form_close(); ?>
