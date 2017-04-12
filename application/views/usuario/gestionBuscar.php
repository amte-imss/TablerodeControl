<?php echo js('usuario/lista_usuarios.js'); ?>

<div ng-class="panelClass" class="row">
    <div class="col col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Usuarios</h3>
            </div>
            <div class="panel-body">
                <div>
                    <?php
                    echo form_open('usuario/lista_usuarios/', array('id' => 'form_usuarios'));
                    ?>

                    <?php
                    if (isset($current_page))
                    {
                        ?>
                        <input id="usuarios_current_page" type="hidden" name="current_page" value="<?php echo $current_page; ?>" />
                    <?php }
                    ?>

                    <div class="form-group">
                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Número de registros a mostrar:</span>
                                <?php
                                echo $this->form_complete->create_element(
                                        array('id' => 'per_page',
                                            'type' => 'dropdown',
                                            'value' => '20',
                                            'options' => array(5 => 5, 10 => 10, 20 => 20, 50 => 50, 100 => 100),
                                            'attributes' => array('name' => 'per_page',
                                                'class' => 'form-control  form-control input-sm',
                                                'data-toggle' => 'tooltip',
                                                'data-placement' => 'top',
                                                'title' => 'Número de registros a mostrar')
                                        )
                                );
                                ?>
                            </div>
                            <?php echo form_error_format('per_page'); ?>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Tipo de orden:</span>
                                <?php
                                echo $this->form_complete->create_element(
                                        array('id' => 'order',
                                            'type' => 'dropdown',
                                            'options' => array(1 => 'Ascendente', 2 => 'Descendente'),
                                            'attributes' => array('name' => 'per_page',
                                                'class' => 'form-control  form-control input-sm',
                                                'data-toggle' => 'tooltip',
                                                'data-placement' => 'top',
                                                'title' => 'Tipo de orden')
                                        )
                                );
                                ?>
                            </div>
                            <?php echo form_error_format('order'); ?>
                        </div>

                        <div class="col-md-4">
                            <?php
                            echo $this->form_complete->create_element(array(
                                'id' => 'btn_submit',
                                'type' => 'submit',
                                'value' => 'Buscar',
                                'attributes' => array(
                                    'class' => 'btn btn-primary',
                                ),
                            ));
                            ?>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                    <!--tablas de usuarios-->

                    <br>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Matrícula</th>
                                <th>Nombre</th>
                                <th>Correo electrónico</th>
                                <th>Delegación</th>
                                <th>Unidad</th>
                                <th>Activo</th>
                                <th>Editar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($usuarios['tabla'] as $row)
                            {
                                ?>
                                <tr>
                                    <td><?php echo $row['matricula']; ?></td>
                                    <td><?php echo $row['nombre']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['name_delegacion']; ?></td>
                                    <td><?php echo $row['name_unidad_ist']; ?></td>
                                    <!-- <td><a href="<?php echo site_url() ?>/usuario/ver_usuario_tabla/<?php echo $row['id_usuario']; ?>">Ver</a></td> -->
                                    <td>
                                        <input id="usuario_chbox_<?php echo $row['id_usuario']; ?>" type="checkbox" <?php echo ($row['activo'] == 1 ? 'checked' : '') ?> onchange="set_status_usuario(<?php echo $row['id_usuario']; ?>)" />
                                    </td>
                                    <td><a href="<?php echo site_url() ?>/usuario/mod/<?php echo $row['id_usuario']; ?>"><i class="glyphicon glyphicon-pencil"></i></a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>


                    <?php
                    if (isset($current_page))
                    {
                        ?>
                        <nav aria-label="...">
                            <ul class="pager">
                                <?php
                                if ($current_page > 0)
                                {
                                    ?>
                                    <li><a onclick="paginar_usuarios(<?php echo $current_page - 1; ?>)">Anterior</a></li>
                                    <?php
                                }
                                ?>
                                <li><a onclick="paginar_usuarios(<?php echo $current_page + 1; ?>)">Siguiente</a></li>
                            </ul>
                        </nav>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
</div>
