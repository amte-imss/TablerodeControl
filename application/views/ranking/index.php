<div ng-class="panelClass" class="row">
    <div class="col col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Ranking</h3>
            </div> <br><br>
            <div class="panel-body">
                <?php if(isset($view_filtros)){
                    echo $view_filtros;
                }
                ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Datos</h3>
            </div> <br><br>
            <div class="panel-body">
                <div id="area_ranking"></div>
            </div>
        </div>
    </div>
</div>

<?php echo js('ranking/index.js'); ?>