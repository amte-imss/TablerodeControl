<script src="<?php echo base_url(); ?>assets/third-party/highcharts/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/modules/exporting.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/fancytree/lib/jquery-ui.custom.js"></script>
<link href="<?php echo base_url(); ?>assets/third-party/fancytree/src/skin-win8/ui.fancytree.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url(); ?>assets/third-party/fancytree/src/jquery.fancytree.js"></script>
<!-- <link href="<?php echo base_url(); ?>assets/third-party/fancytree/lib/prettify.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url(); ?>assets/third-party/fancytree/lib/prettify.js"></script> -->
<?php echo js('informacion_general.js'); ?>
<div class="row">
    <?php echo form_open('', array('id'=>'form_busqueda', 'name'=>'form_busqueda')); ?>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-header" data-background-color="orange">
                    <?php echo $lenguaje['perfil']; ?>
                </div>
                <div class="card-content">
                    <div id="tree3"></div>
                    <div><input type="hidden" id="perfil_seleccion" name="perfil_seleccion"></div>
                    <div><input type="hidden" id="perfil_seleccion_rootkey" name="perfil_seleccion_rootkey"></div>
                    <div><input type="hidden" id="perfil_seleccion_node" name="perfil_seleccion_node"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-header" data-background-color="green">
                    <?php echo $lenguaje['tipo_curso']; ?>
                </div>
                <div class="card-content">
                    <div id="tree2"></div>
                    <div><input type="hidden" id="tipo_curso_seleccion" name="tipo_curso_seleccion"></div>
                    <div><input type="hidden" id="tipo_curso_seleccion_rootkey" name="tipo_curso_seleccion_rootkey"></div>
                    <div><input type="hidden" id="tipo_curso_seleccion_node" name="tipo_curso_seleccion_node"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-header" data-background-color="blue">
                    <?php echo $lenguaje['periodo']; ?>
                </div>
                <div class="card-content">
                    <div class="form-group label-floating is-empty">
                        <!-- <label class="control-label"><?php echo $lenguaje['periodo']; ?></label> -->
                        <?php echo $this->form_complete->create_element(
                            array(
                                'id'=>'periodo_general',
                                'type'=>'dropdown',
                                'options'=>$catalogos['periodo'],
                                'attributes'=>array('class'=>'form-control',
                                    //'onchange'=>"javascript:calcular_totales('informacion_general/calcular_totales', '#form_busqueda');"
                                )
                            )
                        ); ?>
                        <span class="material-input"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <input type="button" id="btn_buscar" name="btn_buscar" class="btn btn-primary pull-right" value="Buscar">
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div id="container_perfil" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript">
    var SOURCE = [
        <?php
        $sub = array();
        foreach ($catalogos['subcategorias'] as $key_sub => $subcategoria) {
            echo '{"title":"'.$subcategoria['subcategoria'].'", "key":'.$key_sub.',
                "expanded":"true","children":[';
            if(isset($subcategoria['elementos'])){
                foreach ($subcategoria['elementos'] as $key_ele => $elemento) {
                    echo '{"title":"'.$elemento.'", "key":'.$key_ele.'},';
                }
            }
            echo ']},';
        }
        ?>
    ];
    var SOURCE2 = [
        <?php
        $sub = array();
        foreach ($catalogos['tipos_cursos'] as $key_tip => $tipos) {
            echo '{"title":"'.$tipos.'", "key":'.$key_tip.', "expanded":"true","children":[]},';
        }
        ?>
    ];
    $(function(){
        buscar_perfil(site_url+'/informacion_general/buscar_perfil', '#form_busqueda');
        $('#btn_buscar').click(function() {
            buscar_perfil(site_url+'/informacion_general/buscar_perfil', '#form_busqueda');
        });
        $("#tree3").fancytree({
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
                $("#perfil_seleccion").val(selKeys.join(","));

                // Get a list of all selected TOP nodes
                var selRootNodes = data.tree.getSelectedNodes(true);
                // ... and convert to a key array:
                var selRootKeys = $.map(selRootNodes, function(node){
                    return node.key;
                });
                $("#perfil_seleccion_rootkey").val(selRootKeys.join(","));
                $("#perfil_seleccion_node").val(selRootNodes.join(","));
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
            // initId: "SOURCE",
            cookieId: "fancytree-Cb3",
            idPrefix: "fancytree-Cb3-"
        });
        $("#tree2").fancytree({
            checkbox: true,
            selectMode: 3,
            source: SOURCE2,
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
            // The following options are only required, if we have more than one tree on one page:
            // initId: "SOURCE",
            cookieId: "fancytree-Cb2",
            idPrefix: "fancytree-Cb2-"
        });
    });
</script>