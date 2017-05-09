<script src="<?php echo base_url(); ?>assets/third-party/highcharts/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/modules/exporting.js"></script>
<?php echo js('informacion_general.js'); ?>
<div class="row">
    <div class="col-lg-1 col-md-0 col-sm-0"></div>
    <div class="col-lg-2 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header" data-background-color="blue">
                <i class="fa fa-user"></i>
            </div>
            <div class="card-content">
                <p class="category" style="min-height:65px;"><?php echo $lenguaje['alumnos_inscritos']; ?></p>
                <h3 class="title" id="total_alumnos_inscritos">-</h3>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header" data-background-color="green">
                <i class="fa fa-check"></i>
            </div>
            <div class="card-content">
                <p class="category" style="min-height:65px;"><?php echo $lenguaje['alumnos_aprobados']; ?></p>
                <h3 class="title" id="total_alumnos_aprobados">-</h3>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header" data-background-color="red">
                <i class="fa fa-close"></i>
            </div>
            <div class="card-content">
                <p class="category" style="min-height:65px;"><?php echo $lenguaje['alumnos_no_aprobados']; ?></p>
                <h3 class="title" id="total_alumnos_no_aprobados">-</h3>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header" data-background-color="orange">
                <i class="fa fa-exclamation-triangle"></i>
            </div>
            <div class="card-content">
                <p class="category" style="min-height:65px;"><?php echo $lenguaje['alumnos_no_acceso']; ?></p>
                <h3 class="title" id="total_alumnos_no_acceso">-</h3>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header" data-background-color="yellow">
                <i class="fa fa-percent"></i>
            </div>
            <div class="card-content">
                <p class="category" style="min-height:65px;"><?php echo $lenguaje['eficiencia_terminal']; ?></p>
                <h3 class="title" id="total_eficiencia_terminal">-</h3>
            </div>
        </div>
    </div>
    <div class="col-lg-1 col-md-0 col-sm-0"></div>
    <?php echo form_open('', array('id'=>'form_busqueda', 'name'=>'form_busqueda')); ?>
    <div class="col-lg-12 col-md-12 col-sm-">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <div>
                <label class="control-label"><?php echo $lenguaje['texto_informativo']; ?>: </label>
                <?php echo $this->form_complete->create_element(
                    array(
                        'id'=>'tipos_busqueda',
                        'type'=>'dropdown',
                        'options'=>$catalogos['tipos_busqueda'],
                        'first'=>array(''=>$lenguaje['seleccione']),
                        'attributes'=>array('class'=>'form-control',
                            'onchange'=>"javascript:mostrar_tipo_grafica('#tipos_busqueda');")
                    )
                ); ?>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <div id="capa_perfil" style="display:none;">
                <div>
                    <div>
                        <label class="control-label"><?php echo $lenguaje['perfil']; ?></label>
                        <?php echo $this->form_complete->create_element(
                            array(
                                'id'=>'perfil',
                                'type'=>'dropdown',
                                'options'=>$catalogos['subcategorias'],
                                'first'=>array(''=>$lenguaje['seleccione']),
                                'attributes'=>array('class'=>'form-control',
                                    'onchange'=>"javascript:calcular_totales(site_url+'/informacion_general/calcular_totales', '#form_busqueda');")
                            )
                        ); ?>
                        <span class="material-input"></span>
                    </div>
                    <span class="material-input"></span>
                </div>
            </div>
            <div id="capa_tipo_curso" style="display:none;">
                <div>
                    <div>
                        <label class="control-label"><?php echo $lenguaje['tipo_curso']; ?></label>
                        <?php echo $this->form_complete->create_element(
                            array(
                                'id'=>'tipo_curso',
                                'type'=>'dropdown',
                                'options'=>$catalogos['tipos_cursos'],
                                'first'=>array(''=>$lenguaje['seleccione']),
                                'attributes'=>array('class'=>'form-control',
                                    'onchange'=>"javascript:calcular_totales(site_url+'/informacion_general/calcular_totales', '#form_busqueda');")
                            )
                        ); ?>
                        <span class="material-input"></span>
                    </div>
                    <span class="material-input"></span>
                </div>
            </div>
            <div id="capa_periodo" style="display:none;">
                <div>
                    <div>
                        <label class="control-label"><?php echo $lenguaje['periodo']; ?></label>
                        <?php echo $this->form_complete->create_element(
                            array(
                                'id'=>'periodo',
                                'type'=>'dropdown',
                                'options'=>$catalogos['implementaciones'],
                                'first'=>array(''=>$lenguaje['seleccione']),
                                'attributes'=>array('class'=>'form-control',
                                    'onchange'=>"javascript:calcular_totales(site_url+'/informacion_general/calcular_totales', '#form_busqueda');")
                                )
                            );
                        ?>
                        <span class="material-input"></span>
                    </div>
                    <span class="material-input"></span>
                </div>
            </div>
            <div id="capa_nivel_atencion" style="display:none;">
                <div>
                    <div>
                        <label class="control-label"><?php echo $lenguaje['nivel_atencion']; ?></label>
                        <?php echo $this->form_complete->create_element(
                            array(
                                'id'=>'nivel_atencion',
                                'type'=>'dropdown',
                                'options'=>$catalogos['nivel_atencion'],
                                'first'=>array(''=>$lenguaje['seleccione']),
                                'attributes'=>array('class'=>'form-control',
                                    'onchange'=>"javascript:calcular_totales(site_url+'/informacion_general/calcular_totales', '#form_busqueda');")
                                )
                            );
                        ?>
                        <span class="material-input"></span>
                    </div>
                    <span class="material-input"></span>
                </div>
            </div>
            <div id="capa_region" style="display:none;">
                <div>
                    <div>
                        <label class="control-label"><?php echo $lenguaje['region']; ?></label>
                        <?php echo $this->form_complete->create_element(
                            array(
                                'id'=>'region',
                                'type'=>'dropdown',
                                'options'=>$catalogos['regiones'],
                                'first'=>array(''=>$lenguaje['seleccione']),
                                'attributes'=>array(
                                    'class'=>'form-control',
                                    'onchange'=>"javascript:calcular_totales(site_url+'/informacion_general/calcular_totales', '#form_busqueda');"
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
            <div id="capa_delegacion" style="display:none;">
                <div>
                    <div>
                        <label class="control-label"><?php echo $lenguaje['delegacion']; ?></label>
                        <?php echo $this->form_complete->create_element(
                            array(
                                'id'=>'delegacion',
                                'type'=>'dropdown',
                                'options'=>$catalogos['delegaciones'],
                                'first'=>array(''=>$lenguaje['seleccione']),
                                'attributes'=>array(
                                    'class'=>'form-control',
                                    'onchange'=>"javascript:calcular_totales(site_url+'/informacion_general/calcular_totales', '#form_busqueda');"
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
            <div id="capa_umae" style="display:none;">
                <div>
                    <div>
                        <label class="control-label"><?php echo $lenguaje['umae']; ?></label>
                        <?php echo $this->form_complete->create_element(
                            array(
                                'id'=>'umae',
                                'type'=>'dropdown',
                                'options'=>$catalogos['unidades_instituto'],
                                'first'=>array(''=>$lenguaje['seleccione']),
                                'attributes'=>array(
                                    'class'=>'form-control',
                                    'onchange'=>"javascript:calcular_totales(site_url+'/informacion_general/calcular_totales', '#form_busqueda');"
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
    </div>
    <div class="clearfix"></div>
    <?php echo form_close(); ?>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div id="container_perfil" style="min-width: 310px; margin: 0 auto; display:none;"></div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div id="container_tipo_curso" style="min-width: 310px; margin: 0 auto; display:none;"></div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div id="container_nivel_atencion" style="min-width: 310px; margin: 0 auto; display:none;"></div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div id="container_region" style="min-width: 310px; margin: 0 auto; display:none;"></div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div id="container_periodo" style="min-width: 310px; margin: 0 auto; display:none;"></div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div id="container_delegacion" style="min-width: 310px; margin: 0 auto; display:none;"></div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div id="container_umae" style="min-width: 310px; margin: 0 auto; display:none;"></div>
    </div>
</div>
<script type="text/javascript">
$( document ).ready(function() {
    calcular_totales_generales(site_url+'/informacion_general/calcular_totales_generales');
});
</script>