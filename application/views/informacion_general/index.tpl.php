<script src="<?php echo base_url(); ?>assets/third-party/highcharts/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/modules/exporting.js"></script>
<?php 
echo js('informacion_general.js');
echo form_open('', array('id'=>'form_busqueda', 'name'=>'form_busqueda')); ?>
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="form-group label-floating is-empty">
            <div class="form-group label-floating is-empty">
                <label class="control-label"><?php echo $lenguaje['perfil']; ?></label>
                <?php echo $this->form_complete->create_element(
                    array(
                        'id'=>'perfil',
                        'type'=>'dropdown',
                        'options'=>$catalogos['subcategorias'],
                        'first'=>array(''=>''),
                        'attributes'=>array('class'=>'form-control',
                            'onchange'=>"javascript:calcular_totales('informacion_general/calcular_totales', '#form_busqueda');")
                    )
                ); ?>
                <span class="material-input"></span>
            </div>
            <span class="material-input"></span>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="form-group label-floating is-empty">
            <div class="form-group label-floating is-empty">
                <label class="control-label"><?php echo $lenguaje['tipo_curso']; ?></label>
                <?php echo $this->form_complete->create_element(
                    array(
                        'id'=>'tipo_curso',
                        'type'=>'dropdown',
                        'options'=>$catalogos['tipos_cursos'],
                        'first'=>array(''=>''),
                        'attributes'=>array('class'=>'form-control',
                            'onchange'=>"javascript:calcular_totales('informacion_general/calcular_totales', '#form_busqueda');")
                    )
                ); ?>
                <span class="material-input"></span>
            </div>
            <span class="material-input"></span>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="form-group label-floating is-empty">
            <div class="form-group label-floating is-empty">
                <label class="control-label"><?php echo $lenguaje['periodo']; ?></label>
                <?php echo $this->form_complete->create_element(
                    array(
                        'id'=>'periodo',
                        'type'=>'dropdown',
                        'options'=>$catalogos['periodo'],
                        'first'=>array(''=>''),
                        'attributes'=>array('class'=>'form-control',
                            'onchange'=>"javascript:calcular_totales('informacion_general/calcular_totales', '#form_busqueda');")
                        )
                    );
                ?>
                <span class="material-input"></span>
            </div>
            <span class="material-input"></span>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="form-group label-floating is-empty">
            <div class="form-group label-floating is-empty">
                <label class="control-label"><?php echo $lenguaje['region']; ?></label>
                <?php echo $this->form_complete->create_element(
                    array(
                        'id'=>'region',
                        'type'=>'dropdown',
                        'options'=>$catalogos['regiones'],
                        'first'=>array(''=>''),
                        'attributes'=>array(
                            'class'=>'form-control',
                            'onchange'=>"javascript:calcular_totales('informacion_general/calcular_totales', '#form_busqueda');"
                            //'autocomplete'=>'off',
                            //'data-toggle'=>'tooltip',
                            //'data-placement'=>'bottom',
                            //'title'=>'Delegaci&oacute;n de trabajo',
                            )
                        )
                    );
                ?>
                <span class="material-input"></span>
            </div>
            <span class="material-input"></span>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header" data-background-color="orange">
                <!-- <i class="material-icons">content_copy</i> -->
                <i class="fa fa-user"></i>
            </div>
            <div class="card-content">
                <p class="category"><?php echo $lenguaje['alumnos_inscritos']; ?></p>
                <h3 class="title" id="total_alumnos_inscritos">-</h3>
            </div>
            <!-- <div class="card-footer">
                <div class="stats">
                    <i class="material-icons text-danger">warning</i> <a href="#pablo">Get More Space...</a>
                </div>
            </div> -->
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header" data-background-color="green">
                <!-- <i class="material-icons">store</i> -->
                <i class="fa fa-check"></i>
            </div>
            <div class="card-content">
                <p class="category"><?php echo $lenguaje['alumnos_aprobados']; ?></p>
                <h3 class="title" id="total_alumnos_aprobados">-</h3>
            </div>
        </div>
    </div>
    <!-- <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header" data-background-color="red">
                <i class="material-icons">info_outline</i>
            </div>
            <div class="card-content">
                <p class="category"><?php //echo $lenguaje['no_accesos']; ?></p>
                <h3 class="title">75</h3>
            </div>
        </div>
    </div> -->
    <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header" data-background-color="blue">
                <i class="fa fa-percent"></i>
            </div>
            <div class="card-content">
                <p class="category"><?php echo $lenguaje['eficiencia_terminal']; ?></p>
                <h3 class="title" id="total_eficiencia_terminal">-</h3>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6">
        <div id="container_perfil" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6">
        <div id="container_tipo_curso" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6">
        <div id="container_region" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6">
        <div id="container_periodo" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    </div>
</div>
<script type="text/javascript">
$( document ).ready(function() {
    calcular_totales(site_url+'/informacion_general/calcular_totales', '#form_busqueda');
});
</script>