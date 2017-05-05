
<ul class="dropdown-menu">
    <li>
        <a href="#">
            <b>Nombre:</b> <?php echo $name_user; ?> <br>
            <b>Matrícula:</b> <?php echo $matricula; ?> <br>
            <b>Categoría:</b> <?php echo $name_categoria . ' [' . $clave_categoria . ']'; ?> <br>
            <?php
            if ($umae)
            {
                ?>
                <b>UMAE:</b> <?php echo $name_unidad_ist.' ['.$clave_unidad.']'; ?> <br>
                <?php
            } else
            {
                ?>
                <b>Delegación:</b> <?php echo $name_delegacion; ?> <br>
                <b>Unidad:</b> <?php echo $name_unidad_ist.' ['.$clave_unidad.']'; ?> <br>
            <?php } ?>
            <div class="ripple-container"></div></a>
    </li>
    <li>
        <a href="<?php echo site_url()?>/perfil_usuario">
            <i class="material-icons">mode_edit</i>
            Editar perfil
            <div class="ripple-container"></div></a>
    </li>
    <li>
        <a href="<?php echo site_url()?>/welcome/cerrar_sesion">
            <i class="fa fa-sign-out"></i>
            Cerrar sesión
        </a>
    </li>
</ul>