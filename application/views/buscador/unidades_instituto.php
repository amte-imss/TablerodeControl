<?php foreach($unidades as $unidad){
    ?>
<li onclick="set_value_unidad(<?php echo $unidad['id_unidad_instituto']; ?>, '<?php echo $unidad['nombre']; ?>')" ><?php echo $unidad['nombre']; ?></li>
<?php
}
