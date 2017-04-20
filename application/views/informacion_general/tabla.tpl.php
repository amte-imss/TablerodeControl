<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th colspan="4" class="text-center" data-background-color="purple"><?php echo $titulo; ?></th>
            </tr>
            <tr>
                <th></th>
                <th class="text-center"><?php echo $lenguaje['alumnos_inscritos']; ?></th>
                <th class="text-center"><?php echo $lenguaje['alumnos_aprobados']; ?></th>
                <th class="text-center"><?php echo $lenguaje['eficiencia_terminal']; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($valores as $llave => $valor) {
                if(isset($valor['cantidad_alumnos_inscritos'])){
                    echo '<tr>
                        <td>'.$llave.'</td>
                        <td><div id="total_alumnos_inscritos" class="text-center">'.$valor['cantidad_alumnos_inscritos'].'</div></td>
                        <td><div id="total_alumnos_aprobados" class="text-center">'.$valor['cantidad_alumnos_certificados'].'</div></td>
                        <td><div id="total_eficiencia_terminal" class="text-center">'.($valor['cantidad_alumnos_inscritos']/$valor['cantidad_alumnos_certificados']).'</div></td>
                    </tr>';
                } else {
                    foreach ($valor as $key_sub => $subvalor) {
                        echo '<tr>
                            <td>'.$llave.' - '.$key_sub.'</td>
                            <td><div id="total_alumnos_inscritos" class="text-center">'.$subvalor['cantidad_alumnos_inscritos'].'</div></td>
                            <td><div id="total_alumnos_aprobados" class="text-center">'.$subvalor['cantidad_alumnos_certificados'].'</div></td>
                            <td><div id="total_eficiencia_terminal" class="text-center">'.($subvalor['cantidad_alumnos_inscritos']/$subvalor['cantidad_alumnos_certificados']).'</div></td>
                        </tr>';
                    }
                }
            } ?>
        </tbody>
    </table>
    <br><br>
</div>
            