<?php
echo js('comparativa/unidad_perfil.js');
echo form_open('comparativa/unidades_perfil', array('id' => 'form_comparativa_umae'));
?>
<div class="row form-group">
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
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">Tipo de perfil:</span>
            <?php
            echo $this->form_complete->create_element(
                    array('id' => 'perfil',
                        'type' => 'dropdown',
                        'first' => array('' => 'Seleccione...'),
                        'options' => $subcategorias,
                        'attributes' => array(
                            'class' => 'form-control  form-control input-sm',
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'top',
                            'title' => 'perfil',
                            'onchange' => 'cmbox_perfil()')
                    )
            );
            ?>
        </div>
        <?php echo form_error_format('perfil'); ?>
    </div>
    <div class="col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-addon">Perfil:</span>
            <?php
            echo $this->form_complete->create_element(
                    array('id' => 'subperfil',
                        'type' => 'dropdown',
                        'first' => array('' => 'Seleccione...'),
                        'options' => [],
                        'attributes' => array(
                            'class' => 'form-control  form-control input-sm',
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'top',
                            'title' => 'subperfil',
                            'onchange' => '')
                    )
            );
            ?>
        </div>
        <?php echo form_error_format('subperfil'); ?>
    </div>
</div>            
<div class="row form-group">
    <div class="col-md-6">
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
                            'title' => 'Unidad 1')
                    )
            );
            ?>
            <ul data-autocomplete-id="1" id="unidad1_autocomplete" style="display:none;"></ul>
        </div>
        <?php echo form_error_format('unidad1'); ?>
    </div>
    <div class="col-md-6">
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
                            'title' => 'Unidad 2')
                    )
            );
            ?>
            <ul data-autocomplete-id="2" id="unidad2_autocomplete" style="display:none;"></ul>
        </div>
        <?php echo form_error_format('unidad2'); ?>
    </div>
</div>            
<div class="row form-group">
    <div class="col-md-6">
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
    <div class="col-md-6">
        <div class="input-group input-group-sm">
            <input type="submit" name="submit" value="Comparar" class="btn btn-primary">
        </div>
    </div>
</div>
<?php echo form_close(); ?>
