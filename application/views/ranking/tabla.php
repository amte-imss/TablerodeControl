<?php
//pr($datos); 
?>
<table id="table_ranking" class="table">
    <thead class="text-primary">
    <th><?php echo ($filtros['umae'] ? 'UMAE' : 'Delegación'); ?></th>
    <th><?php echo ($filtros['tipo'] == 1 || $filtros['tipo'] == '' ? 'Número de Alumnos Aprobados' : 'Eficiencia terminal modificada'); ?></th>
</thead>
<tbody>
    <?php
    foreach ($datos as $row)
    {
        
        if($filtros['umae'] && $usuario['name_unidad_ist'] == $row['nombre']){
            $row['nombre'] = '* '.$row['nombre'];
        }else if($usuario['nombre_grupo_delegacion'] == $row['nombre']){
            $row['nombre'] = '* '.$row['nombre'];
        }            
        
        if ($filtros['tipo'] == 1 || $filtros['tipo'] == '')
        {            
            $value = $row['aprobados'];
        } else if ($row['inscritos'] != $row['no_acceso'])
        {
            $value = ($row['aprobados']) / ($row['inscritos'] - $row['no_acceso']) * 100;
        } else
        {
            $value = 0;
        }
        ?>
        <tr>
            <th><?php echo $row['nombre']; ?></th>
            <td><?php echo intval($value); ?></td>
        </tr>
        <?php
    }
    ?>
</tbody>
</table>



