<?php
pr($texts);
pr($combos);
?>
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/data.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/modules/exporting.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/fancytree/lib/jquery-ui.custom.js"></script>
<link href="<?php echo base_url(); ?>assets/third-party/fancytree/src/skin-win8/ui.fancytree.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url(); ?>assets/third-party/fancytree/src/jquery.fancytree.js"></script>
<!--titulo-->
<div class="col-md-12">
    <div class="card card-plain">
      <div class="card-header" data-background-color="purple">
          <h4 class="title"><?php echo isset($texts["title"]) ? $texts["title"] : ""?></h4>
          <p class="category"><?php echo isset($texts["descripcion"]) ? $texts["descripcion"] : ""?></p>
      </div>
    </div>
  </div>
</div>
<!-- filtros-->
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header" data-background-color="blue" >
        <a data-toggle="collapse" href="#div-filters" aria-expanded="false">
          <h4 class="title">Filtros</h4>
        </a>
      </div>
      <div class="card-content  collapse" id="div-filters">
        <div class="row">
          <div class="col-md-12">
              <b>Región: Centro</b>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group form-group-sm">
              <label class="control-label"><?php echo $texts['lbl_anio']; ?></label>
              <?php echo $this->form_complete->create_element(
                  array(
                      'id'=>'anio',
                      'type'=>'dropdown',
                      'first'=>array(''=>$texts['seleccion']),
                      'options'=>$combos['implementaciones'],
                      'attributes'=>array('class'=>'form-control',
                          //'onchange'=>"javascript:calcular_totales('informacion_general/calcular_totales', '#form_busqueda');"
                      )
                  )
              ); ?>
            </div>
          </div>
          <div class="col-md-4">
            <label class="control-label"><?php echo $texts['lbl_tipo_perfil']; ?></label>
            <?php echo $this->form_complete->create_element(
                array(
                    'id'=>'tipo_perfil',
                    'type'=>'dropdown',
                    'first'=>array(''=>$texts['seleccion']),
                    'options'=>dropdown_options($combos['tipo_perfil'],'id_subcategoria','tipo_perfil'),
                    'attributes'=>array('class'=>'form-control',
                        //'onchange'=>"javascript:calcular_totales('informacion_general/calcular_totales', '#form_busqueda');"
                    )
                )
            ); ?>
          </div>
          <div class="col-md-4">
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header" data-background-color="green">
        <h4 class="title">
          Comparativa de regiones (Nivel estratégico)
        </h4>
      </div>
      <div class="card-content">
        <div class="row">
          <div class="col-md-12">
            <div id="region_chrt" style="min-width: 310px; height: 400px; margin: 0 auto">
            </div>
            <table id="datatable">
            <thead>
                <tr>
                    <th></th>
                    <th>Jane</th>
                    <th>John</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Apples</th>
                    <td>3</td>
                    <td>4</td>
                </tr>
                <tr>
                    <th>Pears</th>
                    <td>2</td>
                    <td>0</td>
                </tr>
                <tr>
                    <th>Plums</th>
                    <td>5</td>
                    <td>11</td>
                </tr>
                <tr>
                    <th>Bananas</th>
                    <td>1</td>
                    <td>1</td>
                </tr>
                <tr>
                    <th>Oranges</th>
                    <td>2</td>
                    <td>4</td>
                </tr>
            </tbody>
        </table>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
<!--script src="<?php echo base_url(); ?>assets/hightcharts/highcharts.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/hightcharts/exporting.js" type="text/javascript"></script-->
<script type="text/javascript">
 $(document).ready(function(){
  //chart
  Highcharts.chart('region_chrt', {
    data: {
        table: 'datatable'
    },
    chart: {
        type: 'column'
    },
    title: {
        text: 'Data extracted from a HTML table in the page'
    },
    yAxis: {
        allowDecimals: false,
        title: {
            text: 'Units'
        }
    },
    tooltip: {
        formatter: function () {
            return '<b>' + this.series.name + '</b><br/>' +
                this.point.y + ' ' + this.point.name.toLowerCase();
        }
    }
});

//tree
var SOURCE = [
   {title: "Tipo de curso", selected: true, tooltip: "Tipo de curso" },
   {title: "Perfil", selected: true,tooltip: "Perfil" },
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
    $("#reporte_tree").fancytree(CFG);

    var SOURCE = [
       {title: "Noroccidente", selected: true, tooltip: "Noroccidente" },
       {title: "Noreste", selected: true,tooltip: "Noreste" },
       {title: "Centro", selected: true,tooltip: "Centro" },
       {title: "Centro sur", selected: true,tooltip: "Centro sur" },
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
        $("#regiones_tree").fancytree(CFG);

 });
</script>
