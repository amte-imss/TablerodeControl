<?php
echo js('chart_options.js');
echo js('comparativa/umae_tipo_curso.js');
echo form_open('comparativa/umae_tipo_curso', array('id' => 'form_comparativa_umae'));
?>
<hr>
<div class="row form-group">
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
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">Nivel de atención:</span>
            <?php
            echo $this->form_complete->create_element(
                    array('id' => 'nivel',
                        'type' => 'dropdown',
                        'first' => array('' => 'Seleccione...'),
                        'options' => $tipos_cursos,
                        'attributes' => array(
                            'class' => 'form-control  form-control input-sm',
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'top',
                            'title' => 'Nivel de atención',
                            'onchange' => '')
                    )
            );
            ?>
        </div>
        <?php echo form_error_format('nivel'); ?>
    </div>    
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">Tipo de unidad:</span>
            <?php
            $tu = array(
                'class' => 'form-control  form-control input-sm',
                'data-toggle' => 'tooltip',
                'data-placement' => 'top',
                'title' => 'TIpo de unidad',
                'onchange' => '');
            if ($no_edit_tipo_unidad)
            {
                $tu += array('disabled' => true);
            }
            echo $this->form_complete->create_element(array('id' => 'tipo_unidad',
                'type' => 'dropdown',
                'first' => array('' => 'Seleccione...'),
                'options' => $tipos_unidades,
                'value' => $tipo_unidad,
                'attributes' => $tu));
            ?>
        </div>
        <?php echo form_error_format('tipo_unidad'); ?>
    </div>
       
</div>            
<hr>
<div class="row form-group">
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">UMAE:</span>
            <?php
            echo $this->form_complete->create_element(
                    array('id' => 'unidad1',
                        'type' => 'dropdown',
                        'first' => array('' => 'Seleccione...'),
                        'options' => $unidades_instituto,
                        'attributes' => array(
                            'class' => 'form-control  form-control input-sm',
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'top',
                            'title' => 'UMAE',
                            'onchange' => '')
                    )
            );
            ?>
        </div>
        <?php echo form_error_format('unidad1'); ?>
    </div>
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">comparar con :</span>
            <?php
            echo $this->form_complete->create_element(
                    array('id' => 'unidad2',
                        'type' => 'dropdown',
                        'first' => array('' => 'Seleccione...'),
                        'options' => (array(0 => 'PROMEDIO')) + $unidades_instituto,
                        'attributes' => array(
                            'class' => 'form-control  form-control input-sm',
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'top',
                            'title' => 'UMAE 2',
                            'onchange' => '')
                    )
            );
            ?>
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
<hr>
<div class="row form-group">
    <div class="col-md-6">
        <div class="input-group input-group-sm">
            <input type="submit" name="submit" value="Comparar" class="btn btn-primary">
        </div>
    </div>
</div>
<?php echo form_close(); ?>
