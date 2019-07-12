<?php
defined('_PUBLIC_ACCESS') or die();
include_once 'config.php';

$editor_error = (!empty($_SESSION['db_message'])) ? $_SESSION['db_message']['submessage'] : false;
unset($_SESSION['db_message']);
if ($id > 0){
    $item = $db->find($table,$columns_item, $conditions_item);
}
?>
<section class="content-header">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="top-left-header"><?php echo $titulo_editor ?></h3>  
        </div>
    </div>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary"> 
                <!-- form start -->
                <form action="./" method="post" accept-charset="utf-8">
                    <input type="hidden" name="seccion" value="<?php echo $seccion?>">
                    <input type="hidden" name="action" value="editor">
                    <input type="hidden" name="id" value="<?php echo $id ?>">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nombre(s) <span class="required_star">*</span></label>
                                <input tabindex="1" type="text" name="nombre" class="form-control" placeholder="Nombre(s)" value="<?php echo $item['nombre']?>" required>
                            </div>
                            <?php
                            if ($editor_error){
                                ?>
                            <div class="alert alert-error" style="padding: 5px !important;">
                                <p></p><p><?php echo $editor_error ?></p><p></p>
                            </div>
                                <?php
                            }?>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Apellido Paterno <span class="required_star">*</span></label>
                                <input tabindex="2" type="text" name="paterno" class="form-control" placeholder="Apellido Paterno" value="<?php echo $item['paterno']?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Apellido Materno </label>
                                <input tabindex="3" type="text" name="materno" class="form-control" placeholder="Apellido Materno" value="<?php echo $item['materno']?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Teléfono de contacto  <span class="required_star">*</span></label>
                                <input tabindex="4" type="text" name="telefono" class="form-control" placeholder="Teléfono de contacto" value="<?php echo $item['telefono']?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Teléfono Celular</label>
                                <input tabindex="5" type="text" name="celular" class="form-control" placeholder="(12) 3456 7890" value="<?php echo $item['celular']?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Correo Electrónico</label>
                                <input tabindex="6" type="text" name="correo" class="form-control" placeholder="correo@proveedor.com" value="<?php echo $item['correo']?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Dirección</label>
                                <input tabindex="7" type="text" name="direccion" class="form-control" placeholder="Calle, número, colonia, ciudad" value="<?php echo $item['direccion']?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Estatus</label>
                                <br>
                                <label><input type="checkbox" class="minimal" name="activo" value="1" <?php echo $item['activo'] == 1 ? 'checked': ''?>> Activo</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fecha de Inicio de Labores</label>
                                <input tabindex="8" type="text" name="fecha_inicio" class="form-control datepicker" placeholder="YYYY-MM-DD" value="<?php echo $item['fecha_inicio']?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fecha de Fin de Labores</label>
                                <input tabindex="9" type="text" name="fecha_fin" class="form-control" placeholder="YYYY-MM-DD" value="<?php echo $item['fecha_fin']?>">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" name="submit" value="submit" class="btn btn-primary">Guardar</button>
                    <a href="<?php echo $site_url?>administracion/<?php echo $seccion?>"><button type="button" class="btn btn-primary">Regresar</button></a>
                </div>
                </form>            
            </div>
        </div>
    </div>
</section>