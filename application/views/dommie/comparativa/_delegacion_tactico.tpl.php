<script src="<?php echo base_url(); ?>assets/third-party/highcharts/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/highcharts/modules/exporting.js"></script>
<script src="<?php echo base_url(); ?>assets/third-party/fancytree/lib/jquery-ui.custom.js"></script>
<link href="<?php echo base_url(); ?>assets/third-party/fancytree/src/skin-win8/ui.fancytree.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url(); ?>assets/third-party/fancytree/src/jquery.fancytree.js"></script>
<div class="row">
  <div class="col-md-3">
    <div class="card">
      <div class="card-header" data-background-color="green">
        Filtros
      </div>
      <div class="card-content">
        <div class="row">
          <div class="col-md-12">
            <p>
              Región: Centro
            </p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group form-group-sm">
              <label class="control-label">Año</label>
              <select class="form-control">
                <option></option>
                <option>2016</option>
                <option>2017</option>
              </select>
              <span class="material-input"></span>
            </div>
          </div>
          <div class="col-md-12">
              <div id="tipo_curso_tree">Delegación
                <ul class="ui-fancytree fancytree-container fancytree-plain" tabindex="0">
                  <li>
                    <span class="fancytree-node fancytree-partsel fancytree-selected fancytree-exp-n fancytree-ico-c">
                      <span class="fancytree-expander"></span>
                      <span class="fancytree-checkbox"></span>
                      <span class="fancytree-title">Chiapas</span>
                    </span>
                  </li>
                  <li>
                    <span class="fancytree-node fancytree-active fancytree-partsel fancytree-selected fancytree-exp-n fancytree-ico-c">
                      <span class="fancytree-expander"></span>
                      <span class="fancytree-checkbox"></span>
                      <span class="fancytree-title">DF sur</span>
                    </span>
                  </li>
                  <li>
                    <span class="fancytree-node fancytree-partsel fancytree-selected fancytree-exp-n fancytree-ico-c">
                      <span class="fancytree-expander"></span>
                      <span class="fancytree-checkbox"></span>
                      <span class="fancytree-title">Guerrero</span>
                    </span>
                  </li>
                  <li class="fancytree-lastsib">
                    <span class="fancytree-node fancytree-lastsib fancytree-partsel fancytree-selected fancytree-exp-nl fancytree-ico-c">
                      <span class="fancytree-expander"></span>
                      <span class="fancytree-checkbox"></span>
                      <span class="fancytree-title">Morelos</span>
                    </span>
                  </li>
                  <li class="fancytree-lastsib">
                    <span class="fancytree-node fancytree-lastsib fancytree-partsel fancytree-selected fancytree-exp-nl fancytree-ico-c">
                      <span class="fancytree-expander"></span>
                      <span class="fancytree-checkbox"></span>
                      <span class="fancytree-title">Oaxaca</span>
                    </span>
                  </li>
                  <li class="fancytree-lastsib">
                    <span class="fancytree-node fancytree-lastsib fancytree-partsel fancytree-selected fancytree-exp-nl fancytree-ico-c">
                      <span class="fancytree-expander"></span>
                      <span class="fancytree-checkbox"></span>
                      <span class="fancytree-title">Puebla</span>
                    </span>
                  </li>
                  <li class="fancytree-lastsib">
                    <span class="fancytree-node fancytree-lastsib fancytree-partsel fancytree-selected fancytree-exp-nl fancytree-ico-c">
                      <span class="fancytree-expander"></span>
                      <span class="fancytree-checkbox"></span>
                      <span class="fancytree-title">Queretaro</span>
                    </span>
                  </li>
                  <li class="fancytree-lastsib">
                    <span class="fancytree-node fancytree-lastsib fancytree-partsel fancytree-selected fancytree-exp-nl fancytree-ico-c">
                      <span class="fancytree-expander"></span>
                      <span class="fancytree-checkbox"></span>
                      <span class="fancytree-title">Tabasco</span>
                    </span>
                  </li>
                  <li class="fancytree-lastsib">
                    <span class="fancytree-node fancytree-lastsib fancytree-partsel fancytree-selected fancytree-exp-nl fancytree-ico-c">
                      <span class="fancytree-expander"></span>
                      <span class="fancytree-checkbox"></span>
                      <span class="fancytree-title">Tlaxcala</span>
                    </span>
                  </li>
                  <li class="fancytree-lastsib">
                    <span class="fancytree-node fancytree-lastsib fancytree-partsel fancytree-selected fancytree-exp-nl fancytree-ico-c">
                      <span class="fancytree-expander"></span>
                      <span class="fancytree-checkbox"></span>
                      <span class="fancytree-title">Veracruz sur</span>
                    </span>
                  </li>
                  <li class="fancytree-lastsib">
                    <span class="fancytree-node fancytree-lastsib fancytree-partsel fancytree-selected fancytree-exp-nl fancytree-ico-c">
                      <span class="fancytree-expander"></span>
                      <span class="fancytree-checkbox"></span>
                      <span class="fancytree-title">Veracruz Nte</span>
                    </span>
                  </li>
                </ul>
              </div>
          </div>
          <div class="col-md-12">
              <div id="tipo_curso_tree">Tipo de curso
                <ul class="ui-fancytree fancytree-container fancytree-plain" tabindex="0">
                  <li>
                    <span class="fancytree-node fancytree-partsel fancytree-selected fancytree-exp-n fancytree-ico-c">
                      <span class="fancytree-expander"></span>
                      <span class="fancytree-checkbox"></span>
                      <span class="fancytree-title">Formación</span>
                    </span>
                  </li>
                  <li>
                    <span class="fancytree-node fancytree-active fancytree-partsel fancytree-selected fancytree-exp-n fancytree-ico-c">
                      <span class="fancytree-expander"></span>
                      <span class="fancytree-checkbox"></span>
                      <span class="fancytree-title">Actualización</span>
                    </span>
                  </li>
                  <li>
                    <span class="fancytree-node fancytree-partsel fancytree-selected fancytree-exp-n fancytree-ico-c">
                      <span class="fancytree-expander"></span>
                      <span class="fancytree-checkbox"></span>
                      <span class="fancytree-title">Capacitación</span>
                    </span>
                  </li>
                </ul>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9">
    <div class="card">
      <div class="card-header" data-background-color="purple">
        <h4 class="title">
          Comparativa de regiones (Nivel estratégico)
        </h4>
      </div>
      <div class="card-content">
        <div class="row">
          <div class="col-md-12">
            <div id="comparativa_chrt" style="min-width: 310px; height: 400px; margin: 0 auto">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12"><br><br><br><br>
            <div id="comparativa_chrt2" style="min-width: 310px; height: 400px; margin: 0 auto">
            </div>
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
   Highcharts.chart('comparativa_chrt', {
       chart: {
           type: 'column'
       },
       title: {
           text: 'Perfiles por región '
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
          return '<b>Región: </b>' + columna + '<br/><b>' +
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
           stack: 'Noroccidente'
       }, {
           name: 'Actualización',
           data: [83, 78, 98,20],
           visible: true,
           stack:'Noroccidente'
       },{
           name: 'Capacitación',
           data: [83, 78, 98,20],
           visible: true,
           stack:'Noroccidente'
       }, {
           name: 'Formación',
           data: [48, 38, 39,30],
           visible: true,
           stack:'Noroeste'
       },{
           name: 'Actualización',
           data: [83, 78, 98,20],
           visible: true,
           stack:'Noroeste'
       }, {
           name: 'Capacitación',
           data: [48, 38, 39,30],
           visible: true,
           stack:'Noroeste'
       },{
           name: 'Formación',
           data: [48, 38, 39,30],
           visible: true,
           stack:'Centro'
       },{
           name: 'Actualización',
           data: [48, 38, 39,30],
           visible: true,
           stack:'Centro'
       },{
           name: 'Capacitación',
           data: [48, 38, 39,30],
           visible: true,
           stack:'Centro'
       },{
           name: 'Formación',
           data: [48, 38, 39,30],
           visible: true,
           stack:'Centro sur'
       },{
           name: 'Actualización',
           data: [48, 38, 39,30],
           visible: true,
           stack:'Centro sur'
       },{
           name: 'Capacitación',
           data: [48, 38, 39,30],
           visible: true,
           stack:'Centro sur'
       },
     ],

   });
   Highcharts.chart('comparativa_chrt2', {
       chart: {
           type: 'column'
       },
       title: {
           text: 'Tipos de curso por región '
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
          return '<b>Región: </b>' + columna + '<br/><b>' +
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
             stack: 'Noroccidente'
         }, {
             name: 'MNF',
             data: [83, 78, 98,20],
             visible: true,
             stack:'Noroccidente'
         },{
             name: 'ENF',
             data: [83, 78, 98,20],
             visible: true,
             stack:'Noroccidente'
         }, {
             name: 'MF',
             data: [48, 38, 39,30],
             visible: true,
             stack:'Noroeste'
         },{
             name: 'MNF',
             data: [83, 78, 98,20],
             visible: true,
             stack:'Noroeste'
         }, {
             name: 'ENF',
             data: [48, 38, 39,30],
             visible: true,
             stack:'Noroeste'
         },{
             name: 'MF',
             data: [48, 38, 39,30],
             visible: true,
             stack:'Centro'
         },{
             name: 'MNF',
             data: [48, 38, 39,30],
             visible: true,
             stack:'Centro'
         },{
             name: 'ENF',
             data: [48, 38, 39,30],
             visible: true,
             stack:'Centro'
         },{
             name: 'MF',
             data: [48, 38, 39,30],
             visible: true,
             stack:'Centro sur'
         },{
             name: 'MNF',
             data: [48, 38, 39,30],
             visible: true,
             stack:'Centro sur'
         },{
             name: 'ENF',
             data: [48, 38, 39,30],
             visible: true,
             stack:'Centro sur'
         },
     ],

   });
 });
</script>
