<?php
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
$comparativas = array('Número de alumnos inscritos ',
    'Número de alumnos aprobados ',
    'Porcentaje de eficiencia terminal modificada ',
    'Número de alumnos no aprobados ');
$etm1 = 0;
if ($datos['dato1']['inscritos'] != $datos['dato1']['no_acceso'])
{
    $etm1 = ($datos['dato1']['aprobados']) / ($datos['dato1']['inscritos'] - $datos['dato1']['no_acceso']) * 100;
    $etm1 = number_format($etm1, 0);
}
$etm2 = 0;
if ($datos['dato2']['inscritos'] != $datos['dato2']['no_acceso'])
{
    $etm2 = ($datos['dato2']['aprobados']) / ($datos['dato2']['inscritos'] - $datos['dato2']['no_acceso']) * 100;
    $etm2 = number_format($etm2, 0);
}
?>
<div style="display:none;">
    <table id="table_inscritos" class="table">
        <thead class="text-primary">
        <th>Delegación</th>
        <th><?php echo $comparativas[0]; ?></th>
        </thead>
        <tbody>
            <tr>
                <th><?php echo $datos['dato1']['nombre'] ?></th>
                <td><?php echo $datos['dato1']['inscritos']; ?></td>            
            </tr>
            <tr>
                <th><?php echo $datos['dato2']['nombre'] ?></th>
                <td><?php echo $datos['dato2']['inscritos']; ?></td>            
            </tr>        
        </tbody>
    </table>

    <table id="table_aprobados" class="table">
        <thead class="text-primary">
        <th>Delegación</th>
        <th><?php echo $comparativas[1]; ?></th>
        </thead>
        <tbody>
            <tr>
                <th><?php echo $datos['dato1']['nombre'] ?></th>
                <td><?php echo $datos['dato1']['aprobados']; ?></td>            
            </tr>
            <tr>
                <th><?php echo $datos['dato2']['nombre'] ?></th>
                <td><?php echo $datos['dato2']['aprobados']; ?></td>            
            </tr>        
        </tbody>
    </table>

    <table id="table_etm" class="table">
        <thead class="text-primary">
        <th>Delegación</th>
        <th><?php echo $comparativas[2]; ?></th>
        </thead>
        <tbody>
            <tr>
                <th><?php echo $datos['dato1']['nombre'] ?></th>
                <td><?php echo $etm1; ?></td>            
            </tr>
            <tr>
                <th><?php echo $datos['dato2']['nombre'] ?></th>
                <td><?php echo $etm2; ?></td>            
            </tr>        
        </tbody>
    </table>

    <table id="table_no_aprobados" class="table">
        <thead class="text-primary">
        <th>Delegación</th>
        <th><?php echo $comparativas[3]; ?></th>
        </thead>
        <tbody>
            <tr>
                <th><?php echo $datos['dato1']['nombre'] ?></th>
                <td><?php echo ($datos['dato1']['inscritos'] - $datos['dato1']['aprobados']); ?></td>            
            </tr>
            <tr>
                <th><?php echo $datos['dato2']['nombre'] ?></th>
                <td><?php echo ($datos['dato2']['inscritos'] - $datos['dato2']['aprobados']); ?></td>            
            </tr>        
        </tbody>
    </table>
</div>