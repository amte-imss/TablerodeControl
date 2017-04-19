<?php echo js('modulo/formulario_modulos_grupo.js'); ?>
<div>
    <?php
    echo form_open('modulo/get_modulos_grupo/', array('id' => 'form_modulos_grupo'));
    ?>
    <input type="hidden" name="grupo" value="<?php echo $grupo; ?>">
    <table class="table table-bordered">
        <thead>
        <th>Modulo</th>        
        <th>Configurador</th>        
        <th>Activo</th>
        </thead>
        <tbody>
            <?php
            foreach ($modulos as $row)
            {
                ?>
                <tr>
                    <td><?php echo $row['nombre']; ?></td>
                    <td>
                        <?php
                        echo $this->form_complete->create_element(
                                array('id' => 'configurador'.$row['id_modulo'],
                                    'type' => 'text',
                                    'attributes' => array('name' => 'configurador'.$row['id_modulo'],
                                        'class' => 'form-control  form-control input-sm',
                                        'data-toggle' => 'tooltip',
                                        'data-placement' => 'top',
                                        'placeholder'  => 'Opción adicional', 
                                        'title' => 'Opción adicional')
                                )
                        );
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $this->form_complete->create_element(
                                array('id' => 'activo'.$row['id_modulo'],
                                    'type' => 'checkbox',
                                    'attributes' => array('name' => 'activo'.$row['id_modulo'],
                                        'class' => 'form-control  form-control input-sm',
                                        'data-toggle' => 'tooltip',
                                        'data-placement' => 'top',
                                        'title' => 'activo', 
                                        'checked' => (empty($row['id_grupo'])? false: true))
                                )
                        );
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <div class="col-md-4">
        <?php
        echo $this->form_complete->create_element(array(
            'id' => 'btn_submit',
            'type' => 'submit',
            'value' => 'Guardar',
            'attributes' => array(
                'class' => 'btn btn-primary',
            ),
        ));
        ?>
    </div>
    <?php
    echo form_close();
    ?>
</div>