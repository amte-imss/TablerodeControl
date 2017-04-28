<script src="<?php echo base_url(); ?>assets/third-party/highcharts/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/modules/exporting.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/fancytree/lib/jquery-ui.custom.js"></script>
<link href="<?php echo base_url(); ?>assets/third-party/fancytree/src/skin-win8/ui.fancytree.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url(); ?>assets/third-party/fancytree/src/jquery.fancytree.js"></script>
<?php echo js('informacion_general.js'); ?>
<div class="row">
    <?php echo form_open('', array('id'=>'form_busqueda', 'name'=>'form_busqueda')); ?>
    <div id="filtros" class="col-lg-12 col-md-12 col-sm-12">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header" data-background-color="blue">
                    <?php echo $lenguaje['filtros']; ?>
                </div>
                <div id="perfil_tree_capa" class="card-content">
                    <div id="anio_capa" class="col-lg-4 col-md-6 col-sm-12">
                        <label class="control-label"><?php echo $lenguaje['anio']; ?></label>
                        <?php echo $this->form_complete->create_element(
                            array(
                                'id'=>'anio',
                                'type'=>'dropdown',
                                'options'=>$catalogos['implementaciones'],
                                'attributes'=>array('class'=>'form-control',
                                )
                            )
                        ); ?>
                        <span class="material-input"></span>
                    </div>
                    <div id="tipos_busqueda_capa" class="col-lg-4 col-md-6 col-sm-12">
                        <label class="control-label"><?php echo $lenguaje['tipo']; ?></label>
                        <?php echo $this->form_complete->create_element(
                            array(
                                'id'=>'tipos_busqueda',
                                'type'=>'dropdown',
                                'options'=>$catalogos['tipos_busqueda'],
                                'first' => array(''=>$lenguaje['seleccione']),
                                'attributes'=>array('class'=>'form-control',
                                    'onchange'=>"javascript: validar_tipos_busqueda('#tipos_busqueda');"
                                )
                            )
                        ); ?>
                        <span class="material-input"></span>
                    </div>
                    <div id="region_capa" class="col-lg-4 col-md-6 col-sm-12" style="display:none;">
                        <label class="control-label"><?php echo $lenguaje['region']; ?></label>
                        <?php
                        echo $this->form_complete->create_element(
                            array(
                                'id'=>'region',
                                'type'=>'dropdown',
                                'options'=>$catalogos['regiones'],
                                'first' => array(''=>$lenguaje['seleccione']),
                                'attributes'=>array('class'=>'form-control',
                                    //'onchange'=>"javascript:data_ajax(site_url+'/informacion_general/cargar_listado/".$tipo_busq."', '#form_busqueda', '#".$tipo_busq."_capa')"
                                    'onchange'=>"javascript: data_ajax(site_url+'/informacion_general/cargar_listado/ud', '#form_busqueda', '#'+$('#tipos_busqueda').val()+'_capa'); $('#unidad_capa').html(''); $('#tipo_unidad_capa').html(''); $('#umae_capa').html('');"
                                )
                            )
                        ); ?>
                        <span class="material-input"></span>
                    </div>
                    <div id="delegacion_capa" class="col-lg-4 col-md-6 col-sm-12"></div>
                    <div id="tipo_unidad_capa" class="col-lg-4 col-md-6 col-sm-12"></div>
                    <div id="unidad_capa" class="col-lg-4 col-md-6 col-sm-12"></div>
                    <div id="umae_capa" class="col-lg-4 col-md-6 col-sm-12"></div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <input type="button" id="btn_limpiar" name="btn_limpiar" class="btn btn-secondary pull-right" value="<?php echo $lenguaje['limpiar_filtros'];?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div id="tabla_tipo_curso">
                    <div id="comparativa_chrt" style="min-width: 310px; height: 400px; margin: 0 auto">
                </div>
            </div><br><br>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div id="tabla_perfil">
                    <div id="comparativa_chrt2" style="min-width: 310px; height: 400px; margin: 0 auto">
                </div>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript">
    function validar_tipos_busqueda(elemento){
        mostrar_loader();
        console.log(elemento);
        console.log($(elemento).val());
        if($(elemento).val()==''){
            $('#region_capa').hide();
        } else {
            $('#region').val('');
            $('#region_capa').show();
        }
        $('#umae_capa').html('');
        $('#delegacion_capa').html('');
        $('#tipo_unidad_capa').html('');        
        ocultar_loader();
    }
    $(function(){
        $('#btn_limpiar').click(function() {
            $('#tipos_busqueda').val('');
            validar_tipos_busqueda('#tipos_busqueda');
        });
    });
    //var SOURCE = [
        <?php
        /*$sub = array();
        foreach ($catalogos['subcategorias'] as $key_sub => $subcategoria) {
            echo '{"title":"'.$subcategoria['subcategoria'].'", "key":'.$key_sub.',
                "expanded":"true", "selected": "true", "children":[';
            if(isset($subcategoria['elementos'])){
                foreach ($subcategoria['elementos'] as $key_ele => $elemento) {
                    echo '{"title":"'.$elemento.'", "selected": "true", "key":'.$key_ele.'},';
                }
            }
            echo ']},';
        }*/
        ?>
    //];
    //var SOURCE2 = [
        <?php
        /*$sub = array();
        foreach ($catalogos['tipos_cursos'] as $key_tip => $tipos) {
            echo '{"title":"'.$tipos.'", "key":'.$key_tip.', selected: true, "children":[]},';
        }*/
        ?>
    //];
    /*function limpiar_filtros_listados(){
        var perfil_tree = $('#perfil_tree').fancytree('getTree');
        perfil_tree.reload(SOURCE);
        var tipo_curso_tree = $('#tipo_curso_tree').fancytree('getTree');
        tipo_curso_tree.reload(SOURCE2);
        $("#temporal_tipos_busqueda").val('');
        setTimeout(function() {   //calls click event after a certain time
           buscar_perfil(site_url+'/informacion_general/buscar_perfil', '#form_busqueda');
        }, 500);
    }*/
    /*function buscar_filtros_listados(path, form_recurso, recurso, destino) {
        if($("#temporal_tipos_busqueda").val()==""){ //Validamos que este vacío el campo para poder realizar el guardado temporal. Nos indica el sentido de la búsqueda
            $("#temporal_tipos_busqueda").val(recurso);
        }
        if($("#temporal_tipos_busqueda").val()==recurso){
            if($('#perfil_seleccion').val()==''){
                $('#perfil_seleccion').val('-1');
            }
            var dataSend = $(form_recurso).serialize();
            //console.log(dataSend);
            $.ajax({
                url: path,
                data: dataSend+'&destino='+destino,
                method: 'POST',
                dataType: 'json',
                beforeSend: function (xhr) {
                    mostrar_loader();
                    $('#no_existe_datos').remove();
                    $('#'+destino+'_tree').hide();
                    $('#div_resultado').hide('slow');
                    $('#container_perfil').html('');
                    $('#tabla_tipo_curso').html('');
                    $('#tabla_perfil').html('');
                }
            })
            .done(function (response) {
                $('#'+destino+'_seleccion').val('');
                $('#'+destino+'_seleccion_rootkey').val('');
                $('#'+destino+'_seleccion_node').val('');
                if(typeof(response.no_datos) != "undefined"){
                    //$('#'+destino+'_tree').html('<?php echo $lenguaje['no_existe_datos']; ?>');
                    $('#'+destino+'_tree').after('<div id="no_existe_datos"><?php echo $lenguaje['no_existe_datos']; ?></div>');
                    $('#'+destino+'_tree').hide();
                    ocultar_loader();
                } else {
                    var tree = $('#'+destino+'_tree').fancytree('getTree');
                    var t = [];
                    $.each(response, function(i, item) {
                        t.push(item)
                    });
                    tree.reload(t);
                    $('#'+destino+'_tree').show('slow');
                    $(".collapse_element").collapse("show");
                    buscar_perfil(site_url+'/informacion_general/buscar_perfil', '#form_busqueda');
                }
            })
            .fail(function (jqXHR, textStatus) {
                //$(elemento_resultado).html("Ocurrió un error durante el proceso, inténtelo más tarde.");
                ocultar_loader();
                console.log(jqXHR);
                console.log(textStatus);
            })
            .always(function () {
                //ocultar_loader();
            });
        }
    }
    $(function(){
        buscar_perfil(site_url+'/informacion_general/buscar_perfil', '#form_busqueda');
        $('#btn_buscar').click(function() {
            buscar_perfil(site_url+'/informacion_general/buscar_perfil', '#form_busqueda');
        });
        $( "#btn_limpiar" ).click(function() {
            limpiar_filtros_listados();
        });
        $("#perfil_tree").fancytree({
            checkbox: true,
            selectMode: 3,
            source: SOURCE,
            icon: false,
            select: function(event, data) {
                // Get a list of all selected nodes, and convert to a key array:
                var selKeys = $.map(data.tree.getSelectedNodes(), function(node){
                    return node.key;
                });
                $("#perfil_seleccion").val(selKeys.join(","));

                // Get a list of all selected TOP nodes
                var selRootNodes = data.tree.getSelectedNodes(true);
                // ... and convert to a key array:
                var selRootKeys = $.map(selRootNodes, function(node){
                    return node.key;
                });
                $("#perfil_seleccion_rootkey").val(selRootKeys.join(","));
                $("#perfil_seleccion_node").val(selRootNodes.join(","));

                ////Código que permite cambiar las opciones del tree
                buscar_filtros_listados(site_url+'/informacion_general/buscar_filtros_listados', '#form_busqueda', 'perfil', 'tipo_curso');
            },
            dblclick: function(event, data) {
                data.node.toggleSelected();
            },
            keydown: function(event, data) {
                if( event.which === 32 ) {
                    data.node.toggleSelected();
                    return false;
                }
            },
            init: function (event, data) {
                data.tree.getRootNode().visit(function (node) {
                    if (node.data.preselected) node.setSelected(true);
                });
            },
            // The following options are only required, if we have more than one tree on one page:
            // initId: "SOURCE",
            cookieId: "fancytree-Cb3",
            idPrefix: "fancytree-Cb3-"
        });
        $("#tipo_curso_tree").fancytree({
            checkbox: true,
            selectMode: 3,
            source: SOURCE2,
            icon: false,
            select: function(event, data) {
                // Get a list of all selected nodes, and convert to a key array:
                var selKeys = $.map(data.tree.getSelectedNodes(), function(node){
                    return node.key;
                });
                $("#tipo_curso_seleccion").val(selKeys.join(","));

                // Get a list of all selected TOP nodes
                var selRootNodes = data.tree.getSelectedNodes(true);
                // ... and convert to a key array:
                var selRootKeys = $.map(selRootNodes, function(node){
                    return node.key;
                });
                $("#tipo_curso_seleccion_rootkey").val(selRootKeys.join(","));
                $("#tipo_curso_seleccion_node").val(selRootNodes.join(","));
            },
            dblclick: function(event, data) {
                data.node.toggleSelected();
            },
            keydown: function(event, data) {
                if( event.which === 32 ) {
                    data.node.toggleSelected();
                    return false;
                }
            },
            init: function (event, data) {
                data.tree.getRootNode().visit(function (node) {
                    if (node.data.preselected) node.setSelected(true);
                });
            },
            // The following options are only required, if we have more than one tree on one page:
            // initId: "SOURCE",
            cookieId: "fancytree-Cb2",
            idPrefix: "fancytree-Cb2-"
        });
    });*/
</script>
<script type="text/javascript">
/*$(document).ready(function(){
   Highcharts.chart('comparativa_chrt', {
       chart: {
           type: 'column'
       },
       title: {
           text: 'Tipos de curso por Perfil '
       },
       subtitle: {
           text: ''
       },
       legend: {
          enabled: false
      },
       xAxis: {
           categories: [
               'Aprobados',
               'No accesos',
               'Inscritos',
               'ETM'
           ],
       },
       yAxis: {
           min: 0,
           title: {
               text: 'Alumnos'
           }
       },
       tooltip: {
         formatter: function () {
           //console.log(this.point);
           var columna = this.series.stackKey.replace('column','');
          return '<b>Perfil: </b>' + columna + '<br/><b>' +
              this.series.name + '</b>: ' + this.y + '<br/>' +
              '<b>Total: </b>' + this.point.stackTotal;

              //
            }
       },
       plotOptions: {
           column: {
               pointPadding: 0.2,
               borderWidth: 0,
               stacking: 'normal',
           }
       },
       series: [
       {
           name: 'Formación',
           data: [49, 71, 106,20],
           visible: true,
           stack: 'MF'
       }, {
           name: 'Actualización',
           data: [83, 78, 98,20],
           visible: true,
           stack:'MF'
       },{
           name: 'Capacitación',
           data: [83, 78, 98,20],
           visible: true,
           stack:'MF'
       }, {
           name: 'Formación',
           data: [48, 38, 39,30],
           visible: true,
           stack:'MNF'
       },{
           name: 'Actualización',
           data: [83, 78, 98,20],
           visible: true,
           stack:'MNF'
       }, {
           name: 'Capacitación',
           data: [48, 38, 39,30],
           visible: true,
           stack:'MNF'
       },{
           name: 'Formación',
           data: [48, 38, 39,30],
           visible: true,
           stack:'MG'
       },{
           name: 'Actualización',
           data: [48, 38, 39,30],
           visible: true,
           stack:'MG'
       },{
           name: 'Capacitación',
           data: [48, 38, 39,30],
           visible: true,
           stack:'MG'
       },{
           name: 'Formación',
           data: [48, 38, 39,30],
           visible: true,
           stack:'Enfermería'
       },{
           name: 'Actualización',
           data: [48, 38, 39,30],
           visible: true,
           stack:'Enfermería'
       },{
           name: 'Capacitación',
           data: [48, 38, 39,30],
           visible: true,
           stack:'Enfermería'
       },
     ],

   });
   Highcharts.chart('comparativa_chrt2', {
       chart: {
           type: 'column'
       },
       title: {
           text: 'Perfiles por tipo de curso '
       },
       subtitle: {
           text: ''
       },
       legend: {
          enabled: false
      },
       xAxis: {
           categories: [
               'Aprobados',
               'No accesos',
               'Inscritos',
               'ETM'
           ],
       },
       yAxis: {
           min: 0,
           title: {
               text: 'Alumnos'
           }
       },
       tooltip: {
         formatter: function () {
           //console.log(this.point);
           var columna = this.series.stackKey.replace('column','');
          return '<b>Tipo de curso: </b>' + columna + '<br/><b>' +
              this.series.name + '</b>: ' + this.y + '<br/>' +
              '<b>Total: </b>' + this.point.stackTotal;

              //
            }
       },
       plotOptions: {
           column: {
               pointPadding: 0.2,
               borderWidth: 0,
               stacking: 'normal',
           }
       },
       series: [
       {
           name: 'MF',
           data: [49, 71, 106,20],
           visible: true,
           stack: 'Actualización'
       }, {
           name: 'MNF',
           data: [83, 78, 98,20],
           visible: true,
           stack:'Actualización'
       },{
           name: 'Enfermería',
           data: [83, 78, 98,20],
           visible: true,
           stack:'Actualización'
       }, {
           name: 'MG',
           data: [48, 38, 39,30],
           visible: true,
           stack:'Actualización'
       },{
           name: 'MF',
           data: [49, 71, 106,20],
           visible: true,
           stack: 'Formación'
       }, {
           name: 'MNF',
           data: [83, 78, 98,20],
           visible: true,
           stack:'Formación'
       },{
           name: 'Enfermería',
           data: [83, 78, 98,20],
           visible: true,
           stack:'Formación'
       }, {
           name: 'MG',
           data: [48, 38, 39,30],
           visible: true,
           stack:'Formación'
       },{
           name: 'MF',
           data: [49, 71, 106,20],
           visible: true,
           stack: 'Capacitación'
       }, {
           name: 'MNF',
           data: [83, 78, 98,20],
           visible: true,
           stack:'Capacitación'
       },{
           name: 'Enfermería',
           data: [83, 78, 98,20],
           visible: true,
           stack:'Capacitación'
       }, {
           name: 'MG',
           data: [48, 38, 39,30],
           visible: true,
           stack:'Capacitación'
       }

     ],

   });

   var SOURCE = [
      {title: "UMF 1 Col. Roma", selected: true },
      {title: "UMF 4 Doctores", selected: true},
      {title: "UMF 9 San Pedro Pinos", selected: true},
      {title: "UMF 18 Contreras", selected: true },
      {title: "UMF 19 Coyoacán", selected: true },
      {title: "UMF 28 Del Valle", selected: true },
      {title: "UMF 38 CFE. Parque España", selected: true },
      {title: "UMF 39 CFE. Xola", selected: true },
      {title: "UMF 140 La Teja", selected: true },
      {title: "UMF 22 Independecia", selected: true },
      {title: "UMF 12 Santa Fe", selected: true },
      {title: "UMF 7 Calz. Tlalpan", selected: true },
      {title: "UMF 12 Santa Fe", selected: true },
      {title: "UMF 15 Ermita Iztapalapa", selected: true },
      {title: "UMF 46 Soriano", selected: true },
      {title: "UMF 21 Fco. del Paso", selected: true },
      {title: "UMF 31 Iztapalapa", selected: true },
      {title: "UMF 160 El Vergel", selected: true },
      {title: "UMF 43 Rojo Gómez", selected: true },
      {title: "UMF 45 Iztacalco", selected: true },
    ];
    var CFG = {
         checkbox: true,
         selectMode: 3,
         source: SOURCE,
         lazyLoad: function(event, ctx) {
           ctx.result = {url: "ajax-sub2.json", debugDelay: 1000};
         },
         loadChildren: function(event, ctx) {
           ctx.node.fixSelection3AfterClick();
         },
         select: function(event, data) {
           // Get a list of all selected nodes, and convert to a key array:
           var selKeys = $.map(data.tree.getSelectedNodes(), function(node){
             return node.key;
           });
           $("#echoSelection3").text(selKeys.join(", "));

           // Get a list of all selected TOP nodes
           var selRootNodes = data.tree.getSelectedNodes(true);
           // ... and convert to a key array:
           var selRootKeys = $.map(selRootNodes, function(node){
             return node.key;
           });
           $("#echoSelectionRootKeys3").text(selRootKeys.join(", "));
           $("#echoSelectionRoots3").text(selRootNodes.join(", "));
         },
         dblclick: function(event, data) {
           data.node.toggleSelected();
         },
         keydown: function(event, data) {
           if( event.which === 32 ) {
             data.node.toggleSelected();
             return false;
           }
         },
         // The following options are only required, if we have more than one tree on one page:
   //        initId: "SOURCE",
         cookieId: "fancytree-Cb3",
         idPrefix: "fancytree-Cb3-"
       };
       $("#unidades_tree").fancytree(CFG);

 });*/
</script>