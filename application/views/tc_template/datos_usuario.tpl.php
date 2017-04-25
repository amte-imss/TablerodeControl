<div class="form-group row">
    <label class="col-lg-1 col-md-6 col-sm-6 col-form-label"><?php echo $lenguaje['nombre']; ?>:</label>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <label class="col-form-label"><?php echo $name_user; ?></label>
    </div>
    <label class="col-lg-1 col-md-6 col-sm-6 col-form-label"><?php echo $lenguaje['matricula']; ?>:</label>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <label class="col-form-label"><?php echo $matricula; ?></label>
    </div>
    <label class="col-lg-1 col-md-6 col-sm-6 col-form-label"><?php echo $lenguaje['categoria']; ?>:</label>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <label class="col-form-label"><?php echo $name_categoria.'('.$clave_categoria.')'; ?></label>
    </div>
    <?php if($umae==true){ ?>
        <label class="col-lg-1 col-md-6 col-sm-6 col-form-label"><?php echo $lenguaje['umae']; ?>:</label>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <label class="col-form-label"><?php echo $name_unidad_ist.'('.$clave_unidad.')'; ?></label>
        </div>
    <?php } else { ?>
        <label class="col-lg-1 col-md-6 col-sm-6 col-form-label"><?php echo $lenguaje['delegacion']; ?>:</label>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <label class="col-form-label"><?php echo $name_delegacion; ?></label>
        </div>
        <label class="col-lg-1 col-md-6 col-sm-6 col-form-label"><?php echo $lenguaje['unidad']; ?>:</label>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <label class="col-form-label"><?php echo $name_unidad_ist.'('.$clave_unidad.')'; ?></label>
        </div>
    <?php } ?>
</div>
