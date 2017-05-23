<script src="<?php echo base_url(); ?>assets/third-party/highcharts/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/modules/exporting.js"></script>
<?php
echo js('comparativa/delegacion.js');
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div id="filtros_capa_header" class="card-header" data-background-color="green" data-toggle="collapse" data-target="#filtros_capa">
                <a href="#" data-toggle="collapse" data-target="#filtros_capa">Filtros<i class="fa fa-arrow-right pull-right" aria-hidden="true"></i><!-- <div class="material-icons pull-right">keyword_arrow_right</div> -->
                </a>
            </div>

            <div id="filtros_capa" class="card-content collapse">
                <?php
                echo form_open('comparativa/umae', array('id' => 'form_comparativa'));
                ?>           
                <div class="row form-group">       
                    <?php
                    if (is_nivel_central($usuario['grupos']))
                    {
                        ?>
                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Agrupamiento:</span>
                                <?php
                                echo $this->form_complete->create_element(
                                        array('id' => 'agrupamiento',
                                            'type' => 'dropdown',
                                            'options' => array(0 => 'Si', 1 => 'No'),
                                            'attributes' => array(
                                                'class' => 'form-control  form-control input-sm',
                                                'data-toggle' => 'tooltip',
                                                'data-placement' => 'top',
                                                'title' => 'Agrupamiento',
                                                'onchange' => 'cmbox_comparativa()'
                                            )
                                        )
                                );
                                ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">Nivel:</span>
                                <?php
                                echo $this->form_complete->create_element(
                                        array('id' => 'umae',
                                            'type' => 'dropdown',
                                            'first' => array('' => 'Seleccione...'),
                                            'options' => array(0 => 'Delegacional', 1 => 'UMAE'),
                                            'attributes' => array(
                                                'class' => 'form-control  form-control input-sm',
                                                'data-toggle' => 'tooltip',
                                                'data-placement' => 'top',
                                                'title' => 'UMAE', 
                                                'onchange' => 'cmbox_comparativa()')
                                        )
                                );
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon">* Tipo de comparativa:</span>
                            <?php
                            echo $this->form_complete->create_element(
                                    array('id' => 'comparativa',
                                        'type' => 'dropdown',
                                        'first' => array('' => 'Seleccione...'),
                                        'options' => $comparativas,
                                        'attributes' => array(
                                            'class' => 'form-control  form-control input-sm',
                                            'data-toggle' => 'tooltip',
                                            'data-placement' => 'top',
                                            'title' => 'Tipo de comparativa',
                                            'onchange' => 'cmbox_comparativa()')
                                    )
                            );
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                echo form_close();
                ?>
                <div id="area_comparativa">

                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div id="area_graph"></div>
</div>     

<div id="area_reportes" class="row" style="display:none;">
    <div class="col-lg-12 col-md-12">
        <div class="card card-nav-tabs">
            <div class="card-header" data-background-color="green">
                <div class="nav-tabs-navigation">
                    <div class="nav-tabs-wrapper">
                        <span class="nav-tabs-title">Comparativa de Alumnos:</span>
                        <ul class="nav nav-tabs" data-tabs="tabs">
                            <li class="active">
                                <a href="#inscritos" data-toggle="tab" aria-expanded="true">
                                    Inscritos
                                    <div class="ripple-container"></div>
                                </a>
                            </li>
                            <li class="">
                                <a href="#aprobados" data-toggle="tab" aria-expanded="false">
                                    Aprobados
                                    <div class="ripple-container"></div>
                                </a>
                            </li>                            

                            <li class="">
                                <a href="#suspendidos" data-toggle="tab" aria-expanded="false">
                                    No Aprobabos
                                    <div class="ripple-container"></div>
                                </a>
                            </li>
                            <li class="">
                                <a href="#etm" data-toggle="tab" aria-expanded="false">
                                    Eficiencia Terminal Modificada
                                    <div class="ripple-container"></div>
                                </a>
                            </li>
                        </ul>                        
                    </div>
                </div>
            </div>
            <div class="card-content">
                <div class="tab-content">
                    <!--inscritos-->
                    <div class="tab-pane active" id="inscritos">
                        <div class="col-md-12">                            
                            <div id="area_graph0"></div>
                            <div id="alert-comparativa0" class="alert alert-warning alert-comparativa" style="display: none">
                                <span>
                                    No existen resultados para esa busqueda, intente con otros filtros por favor.
                                </span>
                            </div>
                        </div>
                    </div>

                    <!--aprobados-->
                    <div class="tab-pane" id="aprobados">
                        <div class="col-md-12">                            
                            <div id="area_graph1"></div>
                            <div id="alert-comparativa1" class="alert alert-warning alert-comparativa" style="display: none">
                                <span>
                                    No existen resultados para esa busqueda, intente con otros filtros por favor.
                                </span>
                            </div>
                        </div>
                    </div>
                    <!--etm-->
                    <div class="tab-pane" id="suspendidos">
                        <div class="col-md-12">                           
                            <div id="area_graph3"></div>
                            <div id="alert-comparativa3" class="alert alert-warning alert-comparativa" style="display: none">
                                <span>
                                    No existen resultados para esa busqueda, intente con otros filtros por favor.
                                </span>
                            </div>
                        </div>
                    </div>
                    <!--suspendodos-->
                    <div class="tab-pane" id="etm">
                        <div class="col-md-12">           
                            <div id="area_graph2"></div>
                            <div id="alert-comparativa2" class="alert alert-warning alert-comparativa" style="display: none">
                                <span>
                                    No existen resultados para esa busqueda, intente con otros filtros por favor.
                                </span>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>