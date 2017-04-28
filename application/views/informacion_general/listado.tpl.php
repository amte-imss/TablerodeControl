<label class="control-label"><?php echo $form['label']; ?></label>
<?php echo $this->form_complete->create_element(
    array(
        'id'=>$tipo,
        'type'=>'dropdown',
        'first' => array(''=>$form['seleccione']),
        'options' => $datos,
        'attributes'=>array('class'=>'form-control',
            
        )+$form['evento']
    )
); ?>
<span class="material-input"></span>  