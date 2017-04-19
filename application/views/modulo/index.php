<?php
if ($full_view == 1)
{
    ?>
    <?php echo js('modulo/index.js'); ?>
    <button type="button" onclick="form_save()" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
        Nuevo
    </button>
    <div id="area_modulos"> 
    <?php } ?>
    <div ng-class="panelClass" class="row">
        <div class="col col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Modulos</h3>
                </div>
                <div class="panel-body">
                    <div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>URL</th>
                                    <th>Tipo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($modulos as $row)
                                {
                                    ?>
                                    <tr>
                                        <td><a onclick="get_info_modulo(<?php echo $row['id_modulo']; ?>)" data-toggle="modal" data-target="#myModal"><?php echo $row['nombre']; ?></a></td>
                                        <td><?php echo $row['url']; ?></td>
                                        <td><?php echo $row['configurador']; ?></td>
                                        <!--<td>Editar</td>-->
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    if ($full_view == 1)
    {
        ?>
    </div>
    <!-- Button trigger modal -->
    <button type="button" onclick="form_save()" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
        Nuevo
    </button>
<?php } ?>
