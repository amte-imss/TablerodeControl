<div id="<?php echo $id; ?>" class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th colspan="6" class="text-center" data-background-color="purple"><?php echo $titulo; echo imprimir_elemento_html('#'.$id); ?></th>
            </tr>
            <tr>
                <th></th>
                <th class="text-center"><?php echo $lenguaje['alumnos_inscritos']; ?></th>
                <th class="text-center"><?php echo $lenguaje['alumnos_aprobados']; ?></th>
                <th class="text-center"><?php echo $lenguaje['alumnos_no_aprobados']; ?></th>
                <th class="text-center"><?php echo $lenguaje['alumnos_no_acceso']; ?></th>
                <th class="text-center"><?php echo $lenguaje['eficiencia_terminal']; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($valores as $llave => $valor) {
                if(isset($valor['cantidad_alumnos_inscritos'])){
                    $tmp_llave = explode('_', $llave);
                    echo '<tr>
                        <td>'.(isset($tmp_llave[1]) ? $tmp_llave[1] : $llave ).'</td>
                        <td><div id="total_alumnos_inscritos" class="text-center">'.$valor['cantidad_alumnos_inscritos'].'</div></td>
                        <td><div id="total_alumnos_aprobados" class="text-center">'.$valor['cantidad_alumnos_certificados'].'</div></td>
                        <td><div id="total_alumnos_no_aprobados" class="text-center">'.$valor['cantidad_no_aprobados'].'</div></td>
                        <td><div id="total_alumnos_no_acceso" class="text-center">'.$valor['cantidad_no_accesos'].'</div></td>
                        <td><div id="total_eficiencia_terminal" class="text-center">'.(calcular_eficiencia_terminal($valor['cantidad_alumnos_inscritos'], $valor['cantidad_alumnos_certificados'], $valor['cantidad_no_accesos'])).'</div></td>
                    </tr>';
                }
                if(isset($valor['elementos'])){
                    foreach ($valor['elementos'] as $key_sub => $subvalor) {
                        $tmp_key_sub = explode('_', $key_sub);
                        echo '<tr>
                            <td>- '.$tmp_key_sub[1].'</td>
                            <td><div id="total_alumnos_inscritos" class="text-center">'.$subvalor['cantidad_alumnos_inscritos'].'</div></td>
                            <td><div id="total_alumnos_aprobados" class="text-center">'.$subvalor['cantidad_alumnos_certificados'].'</div></td>
                            <td><div id="total_alumnos_no_aprobados" class="text-center">'.$subvalor['cantidad_no_aprobados'].'</div></td>
                            <td><div id="total_alumnos_no_acceso" class="text-center">'.$subvalor['cantidad_no_accesos'].'</div></td>
                            <td><div id="total_eficiencia_terminal" class="text-center">'.(calcular_eficiencia_terminal($subvalor['cantidad_alumnos_inscritos'], $subvalor['cantidad_alumnos_certificados'], $subvalor['cantidad_no_accesos'])).'</div></td>
                        </tr>';
                    }
                }
            } ?>
        </tbody>
    </table>
    <br><br>
</div>
            