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
        <label class="col-form-label"><?php echo $name_categoria; ?></label>
    </div>
    <?php 
    if(in_array($grupos[0]['id_grupo'], array(En_grupos::NIVEL_CENTRAL, En_grupos::ADMIN, En_grupos::SUPERADMIN))) { ?>
        <!-- <label class="col-lg-4 col-md-6 col-sm-6 col-form-label"><?php echo $lenguaje['umae']; ?>:</label> -->
        <div class="col-lg-4 col-md-6 col-sm-6">
            <label class="col-form-label"><?php echo $lenguaje['nivel_central']; ?></label>
        </div>
        <label class="col-lg-1 col-md-6 col-sm-6 col-form-label"><?php echo $lenguaje['direccion_normativa']; ?>:</label>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <label class="col-form-label"><?php echo $name_unidad_ist; ?></label>
        </div>
        <?php 
    } else {
        if($umae==true){ ?>
            <label class="col-lg-1 col-md-6 col-sm-6 col-form-label"><?php echo $lenguaje['umae']; ?>:</label>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <label class="col-form-label"><?php echo $name_unidad_ist; ?></label>
            </div>
        <?php } else { ?>
            <label class="col-lg-1 col-md-6 col-sm-6 col-form-label"><?php echo $lenguaje['delegacion']; ?>:</label>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <label class="col-form-label"><?php echo $nombre_grupo_delegacion; ?></label>
            </div>
            <?php if(in_array($grupos[0]['id_grupo'], array(En_grupos::N1_CEIS,En_grupos::N1_DH,En_grupos::N1_DUMF,En_grupos::N1_DEIS,En_grupos::N1_DM,En_grupos::N1_JDES))) { ?>
                <label class="col-lg-1 col-md-6 col-sm-6 col-form-label"><?php echo $lenguaje['unidad']; ?>:</label>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <label class="col-form-label"><?php echo $name_unidad_ist; ?></label>
                </div>
            <?php }
        }
    }?>
</div>