<script src="<?php echo base_url(); ?>assets/third-party/highcharts/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/modules/exporting.js"></script>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div id="filtros_capa_header" class="card-header" data-background-color="green" data-toggle="collapse" data-target="#filtros_capa">
                <a href="#" data-toggle="collapse" data-target="#filtros_capa">Filtros<i class="fa fa-arrow-right pull-right" aria-hidden="true"></i><!-- <div class="material-icons pull-right">keyword_arrow_right</div> -->
                </a>
            </div>
            <?php
            echo js('chart_options.js');
            echo js('ranking/index.js');
            echo form_open('ranking/', array('id' => 'form_ranking'));
            ?>
            <div id="filtros_capa" class="card-content collapse">
                <?php
//pr($usuario);
                if (isset($usuario['central']))
                {
                    ?>
                    <div class="row form-group">                       
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
                                                'title' => 'UMAE')
                                        )
                                );
                                ?>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon"><i class="material-icons cores-helper" data-help="agrupamiento">help</i> Agrupamiento:</span>
                                <?php
                                echo $this->form_complete->create_element(
                                        array('id' => 'agrupamiento',
                                            'type' => 'dropdown',
                                            'options' => array(1 => 'Si', 0 => 'No'),
                                            'attributes' => array(
                                                'class' => 'form-control  form-control input-sm',
                                                'data-toggle' => 'tooltip',
                                                'data-placement' => 'top',
                                                'title' => 'Agrupamiento',
                                            )
                                        )
                                );
                                ?>
                            </div>
                        </div>
                    </div>            
                    <?php
                }
                ?>
                <div class="row form-group">                    
                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon">Programa:</span>
                            <?php
                            echo $this->form_complete->create_element(
                                    array('id' => 'programa',
                                        'type' => 'dropdown',
                                        'first' => array('' => 'Seleccione...'),
                                        'options' => $programas,
                                        'attributes' => array(
                                            'class' => 'form-control  form-control input-sm',
                                            'data-toggle' => 'tooltip',
                                            'data-placement' => 'top',
                                            'title' => 'Programa')
                                    )
                            );
                            ?>
                        </div>
                        <?php echo form_error_format('programa'); ?>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon">Reporte:</span>
                            <?php
                            echo $this->form_complete->create_element(
                                    array('id' => 'tipo',
                                        'type' => 'dropdown',
                                        'first' => array('' => 'Seleccione...'),
                                        'options' => $graficas,
                                        'attributes' => array(
                                            'class' => 'form-control  form-control input-sm',
                                            'data-toggle' => 'tooltip',
                                            'data-placement' => 'top',
                                            'title' => 'Tipo')
                                    )
                            );
                            ?>
                        </div>
                        <?php echo form_error_format('tipo'); ?>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon">* Año:</span>
                            <?php
                            echo $this->form_complete->create_element(
                                    array('id' => 'periodo',
                                        'type' => 'dropdown',
                                        'first' => array('' => 'Seleccione...'),
                                        'options' => $periodos,
                                        'attributes' => array(
                                            'class' => 'form-control  form-control input-sm',
                                            'data-toggle' => 'tooltip',
                                            'data-placement' => 'top',
                                            'title' => 'Region')
                                    )
                            );
                            ?>
                        </div>
                        <?php echo form_error_format('periodo'); ?>
                    </div>
                </div>
                <br>
                <div class="row">
                    <input type="submit" value="Buscar" class="btn btn-primary">
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo form_close(); ?>

<div class="row">

    <?php
    if (isset($grafica))
    {
        echo $grafica;
    }
    ?>    
    <div id="area_table">
        <?php
        if (isset($tabla))
        {
            echo $tabla;
        }
        ?>
    </div>
</div>

<div id="alert-ranking" class="alert alert-warning alert-comparativa" style="display: none">
    <span>
        No existen resultados para esa busqueda, intente con otros filtros por favor.
    </span>
</div>