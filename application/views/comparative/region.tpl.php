
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/data.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/modules/exporting.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/fancytree/lib/jquery-ui.custom.js"></script>
<link href="<?php echo base_url(); ?>assets/third-party/fancytree/src/skin-win8/ui.fancytree.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url(); ?>assets/third-party/fancytree/src/jquery.fancytree.js"></script>
<!--titulo
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
          <div class="col-md-2">
            <?php
            $cfg = array(
              "title"=>"Tipo de reporte",
              'id'=>"tipo_reporte",
            );
            echo dropdown($cfg,array("tc"=>"Tipo de curso","p"=>"Perfil"));
            ?>
          </div>
          <div class="col-md-2">
            <?php
            $cfg = array(
              "title"=>"Perfil",
              'id'=>"perfil",
              "subseccion"=>"perfil",
            );
            echo dropdown($cfg,$combos["subcategorias"],$combos["perfil"]);
            ?>
          </div>
          <div class="col-md-2">
            <?php
            $cfg = array(
              "title"=>"Tipo de curso",
              'id'=>"tipo_curso",
            );
            echo dropdown($cfg,$combos["tipos_cursos"]);
            ?>
          </div>
          <!--div class="col-md-2">
            <?php
            /*$cfg = array(
              "title"=>"Reporte",
              'id'=>"reporte",
            );
            echo dropdown($cfg,array(
              "I"=>"Inscritos",
              "A"=>"Aprobados",
              "NA"=>"No aprobados",
              "ETM"=>"Eficiencia Terminal Modificada",
            ));*/
            ?>
          </div-->
          <div class="col-md-2">
            <?php
            $cfg = array(
              "title"=>"Año",
              'id'=>"anio",
            );
            echo dropdown($cfg,$combos["implementaciones"]);
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
// pr($texts);
// pr($combos);
// pr($comparativa);
?>
<?php
$inscritos = "";
$aprobados = "";
$suspendidos = "";
$etm = "";
$chart_title = "Comparativa de regiones";
$filtros = "";
if(is_array($comparativa)){
  foreach($comparativa as $region){
    //pr($region);
    $inscritos .= "<tr>
                    <th>".$region["region"]."</th>
                    <td>".$region["inscritos"]."</td>
                  </tr>";
    $aprobados .= "<tr>
                    <th>".$region["region"]."</th>
                    <td>".$region["aprobados"]."</td>
                  </tr>";
    $suspendidos .= "<tr>
                    <th>".$region["region"]."</th>
                    <td>".$region["suspendidos"]."</td>
                  </tr>";
    $etm .= "<tr>
                    <th>".$region["region"]."</th>
                    <td>".$region["etm"]."</td>
                  </tr>";
    if(isset($region["tipo_curso"])){
      $filtros = "Tipo de curso: ".$region["tipo_curso"];
    }elseif(isset($region["perfil"])){
      $filtros = "Peril: ".$region["perfil"];
    }
  }
  ?>
  <div class="row">
    <div class="col-lg-12 col-md-12">
  	   <div class="card card-nav-tabs">
  	      <div class="card-header" data-background-color="green">
  	         <div class="nav-tabs-navigation">
  	            <div class="nav-tabs-wrapper">
  									<span class="nav-tabs-title">Comparativa de Alumnos:</span>
  									<ul class="nav nav-tabs" data-tabs="tabs">
  										<li class="active">
  											<a href="#inscritos" data-toggle="tab">
  												Inscritos
  										    <div class="ripple-container"></div>
                        </a>
  										</li>
  										<li class="">
  											<a href="#aprobados" data-toggle="tab">
  												Aprobados
  				                <div class="ripple-container"></div>
                        </a>
  										</li>
                      <li class="">
  											<a href="#suspendidos" data-toggle="tab">
  												Suspendidos
  				                <div class="ripple-container"></div>
                        </a>
  										</li>
                      <li class="">
  											<a href="#etm" data-toggle="tab">
  												Eficiencia Terminal Modificada
  				                <div class="ripple-container"></div>
                        </a>
  										</li>
  									</ul>
                    <p class="category">
                    <?php
                    echo $filtros;                    ?>
                    </p>
  							</div>
  						</div>
  					</div>

  					<div class="card-content">
  						<div class="tab-content">
                <!--inscritos-->
  			        <div class="tab-pane active" id="inscritos">
                  <div class="col-md-12">
                    <div id="chrt_inscritos" style="min-width: 310px; height: 400px; margin: 0 auto">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <table id="table_inscritos" class="table">
                      <thead class="text-primary">
                          <tr>
                              <th>Región</th>
                              <th>Número de Alumnos inscritos</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php echo $inscritos;?>
                      </tbody>
                    </table>
                  </div>
  							</div>

                <!--aprobados-->
  							<div class="tab-pane" id="aprobados">
                  <div class="col-md-12">
                    <div id="chrt_aprobados" style="min-width: 310px; height: 400px; margin: 0 auto">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <table id="table_aprobados" class="table">
                      <thead class="text-primary">
                          <tr>
                              <th>Región</th>
                              <th>Número de Alumnos aprobados</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php echo $aprobados;?>
                      </tbody>
                    </table>
                  </div>
  							</div>

                <!--suspendodos-->
  							<div class="tab-pane" id="suspendidos">
                  <div class="col-md-12">
                    <div id="chrt_suspendidos" style="min-width: 310px; height: 400px; margin: 0 auto">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <table id="table_suspendidos" class="table">
                      <thead class="text-primary">
                          <tr>
                              <th>Región</th>
                              <th>Número de Alumnos suspendidos</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php echo $suspendidos;?>
                      </tbody>
                    </table>
                  </div>
  							</div>

                <!--etm-->
                <div class="tab-pane" id="etm">
                  <div class="col-md-12">
                    <div id="chrt_etm" style="min-width: 310px; height: 400px; margin: 0 auto">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <table id="table_etm" class="table">
                      <thead class="text-primary">
                          <tr>
                              <th>Región</th>
                              <th>Porcentaje de Eficiencia Terminal Modificada</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php echo $etm;?>
                      </tbody>
                    </table>
                  </div>
  							</div>
  						</div>
  					</div>
  	     </div>
  		</div>
  </div>

  <!--script src="<?php echo base_url(); ?>assets/hightcharts/highcharts.js" type="text/javascript"></script>
  <script src="<?php echo base_url(); ?>assets/hightcharts/exporting.js" type="text/javascript"></script-->
  <?php echo js("chart_options.js");?>
  <script type="text/javascript">
    function chart(id_chart, tabla,titulo,ytext,color){
      Highcharts.chart(id_chart, {
        data: {
            table: tabla
        },
        chart: {
            type: 'column'
        },
        colors: color,
        title: {
            text: titulo
        },
        yAxis: {
            allowDecimals: false,
            title: {
                text: ytext
            }
        },
        tooltip: {
            formatter: function () {
                return '<b>Región:</b> '+this.point.name+'<br/>' +
                    this.series.name+': '+this.point.y;
            }
        }
      });
    }
     $(document).ready(function(){
      //chart
        chart("chrt_inscritos", "table_inscritos","","Número de Alumnos Inscritos",['#0090b9']);
        chart("chrt_aprobados", "table_aprobados","","Número de Alumnos Aprobados",['#43a886']);
        chart("chrt_suspendidos", "table_suspendidos","","Número de Alumnos suspendidos",['#ef5350']);
        chart("chrt_etm", "table_etm","","Porcentaje de Eficiencia Terminal Modificada",['#FCB220']);
      });
  </script>
<?php }?>