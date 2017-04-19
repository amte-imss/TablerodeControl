<div ng-class="panelClass" class="row">
    <div class="col col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Registro de usuario</h3>
            </div> <br><br>
            <div class="panel-body">
                <div class="container" style="text-aligne:center; width: 650px; text-align: left;">
                    <!--form usuario completo-->
                    <?php
                    if (isset($status) && $status)
                    {
                        echo html_message('Usuario actualizado con éxito', 'success');
                    }
                    ?>
                    <?php
                    echo form_open('usuario/mod/' . $usuarios['id_usuario'], array('id' => 'form_actualizar'));
                    ?>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Matrícula: </label>
                        <div class="col-md-6 input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <?php
                            echo $this->form_complete->create_element(array('id' => 'matricula', 'type' => 'number', 'value' => $usuarios['matricula'], 'attributes' => array('name' => 'matricula', 'readonly' => ' ', 'class' => 'form-control')));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Correo electrónico: </label>
                        <div class="col-md-6 input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <?php
                            echo $this->form_complete->create_element(array('id' => 'email', 'type' => 'email', 'value' => $usuarios['email'], 'attributes' => array('name' => 'email', 'class' => 'form-control')));
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Delegación: </label>
                        <div class="col-md-6 input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <?php
                            echo $this->form_complete->create_element(array('id' => 'delegacion', 'type' => 'dropdown', 'value' => $usuarios['clave_delegacional'], 'options' => $delegaciones, 'first' => array('' => ''), 'attributes' => array('name' => 'delegacion', 'class' => 'form-control')));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Categoría: </label>
                        <div class="col-md-6 input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <?php
                            // echo $this->form_complete->create_element(array('id' => 'categoria', 'type' => 'dropdown', 'value' => $usuarios['clav'], 'options' => $unidad_instituto, 'first' => array('' => ''), 'attributes' => array('name' => 'unidad', 'class' => 'form-control')));
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Unidad: </label>
                        <div class="col-md-6 input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <?php
                            //echo $this->form_complete->create_element(array('id' => 'unidad', 'type' => 'dropdown', 'value' => $usuarios['id_unidad_instituto'], 'options' => $unidad_instituto, 'first' => array('' => ''), 'attributes' => array('name' => 'unidad', 'class' => 'form-control')));
                            ?>
                        </div>
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <label class="col-md-4 control-label"></label>
                    <button id="submit" name="submit" type="submit" class="btn btn-success"  style=" background-color:#008EAD">Guardar <span class=""></span></button>
                </div>




                <?php echo form_close(); ?>

            </div>
        </div>
    </div>
</div>

<div ng-class="panelClass" class="row">
    <div class="col col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Constraseña de usuario</h3>
            </div> <br><br>
            <div class="panel-body">
                <div class="container" style="text-aligne:center; width: 650px; text-align: left;">
                    <!--form usuario completo-->
                    <?php
                    if (isset($status_password) && $status_password && $status_password == 1)
                    {
                        echo html_message('Contraseña actualizada con éxito', 'success');
                    } else if (isset($status_password) && $status_password && $status_password == 2)
                    {
                        echo html_message('Datos inválidos', 'danger');
                    }
                    ?>
                    <?php
                    echo form_open('usuario/update_password/' . $usuarios['id_usuario'], array('id' => 'form_actualizar_password'));
                    ?>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Contraseña: </label>
                        <div class="col-md-6 input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <?php
                            echo $this->form_complete->create_element(array('id' => 'pass', 'type' => 'password', 'attributes' => array('name' => 'pass', 'class' => 'form-control')));
                            ?>
                        </div>
                        <?php echo form_error_format('pass'); ?>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">Confirmar contraseña: </label>
                        <div class="col-md-6 input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <?php
                            echo $this->form_complete->create_element(array('id' => 'pass_confirm', 'type' => 'password', 'attributes' => array('name' => 'pass_confirm', 'class' => 'form-control')));
                            ?>
                        </div>
                        <?php echo form_error_format('pass_confirm'); ?>
                    </div>

                    <br>
                    <div class="form-group">
                        <label class="col-md-4 control-label"></label>
                        <button id="submit" name="submit" type="submit" class="btn btn-success"  style=" background-color:#008EAD">Guardar <span class=""></span></button>
                    </div>
                    <?php echo form_close(); ?>

                </div>
            </div>
        </div>
    </div>
</div>


<div ng-class="panelClass" class="row">
    <div class="col col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Grupos</h3>
            </div> <br><br>
            <div class="panel-body">
                <?php
                echo form_open('usuario/test/' . $usuarios['id_usuario'], array('id' => 'form_actualizar'));
                ?>

                <div class="form-group">
                    <label class="col-md-4 control-label">Confirmar contraseña: </label>
                        <?php
                        echo $this->form_complete->create_element(array('id' => 'pass_confirm', 'type' => 'checkbox', 'value' => 'as','attributes' => array('name' => 'pass_confirm', 'class' => 'form-control')));
                        ?>
                </div>

                <br>
                <div class="form-group">
                    <label class="col-md-4 control-label"></label>
                    <button id="submit" name="submit" type="submit" class="btn btn-success"  style=" background-color:#008EAD">Guardar <span class=""></span></button>
                </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
