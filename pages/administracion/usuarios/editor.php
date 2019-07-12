<?php
defined('_PUBLIC_ACCESS') or die();
include_once 'config.php';
$editor_error = (!empty($_SESSION['db_message'])) ?  $_SESSION['db_message']['submessage'] . ': ' .  $_SESSION['db_message']['message'] : false;
unset($_SESSION['db_message']);
if ($id > 0){
    $item = $db->find($table,$columns_item, $conditions_item);
} else {
    $item['contrasena'] = generateRandomString();
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nombre de Usuario <span class="required_star">*</span></label>
                                <input tabindex="1" type="text" name="usuario" class="form-control" placeholder="Nombre de Usuario" value="<?php echo $item['usuario']?>" required>
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Contraseña</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="contrasena" data-visible="0" value="<?php echo $item['contrasena']?>">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default toggle-pass"><i class="fa fa-eye-slash"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>PIN</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="pin" data-visible="0" value="<?php echo $item['pin']?>">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default toggle-pass"><i class="fa fa-eye-slash"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Es Empleado</label>
                                <br>
                                <label><input type="checkbox" tabindex="2" class="minimal" id="check-es-empleado" name="es_empleado" value="1" <?php echo $item['activo'] == 1 ? 'checked': ''?>>  Sí Es Empleado</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Empleado</label>
                                <select class="form-control select2 select-empleados" name="id_empleado" <?php echo $item['es_empleado'] == 1 ? '' : 'disabled'?> >
                                    <option value="">Selecciona uno</option>
                                    <?php
                                    foreach($empleados as $c){
                                        echo '<option value="' . $c['id'] . '" ' . ($c['id'] == $item['id_rol'] ? 'selected' : '') . ' >' . $c['nombre'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nombre(s) <span class="required_star">*</span></label>
                                <input tabindex="4" type="text" name="nombre" class="form-control" placeholder="Nombre" value="<?php echo $item['nombre']?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Apellidos <span class="required_star">*</span></label>
                                <input tabindex="5" type="text" name="apellidos" class="form-control" placeholder="Apellidos" value="<?php echo $item['apellidos']?>" required>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Teléfono de contacto  <span class="required_star">*</span></label>
                                <input tabindex="6" type="text" name="telefono" class="form-control" placeholder="Teléfono de contacto" value="<?php echo $item['telefono']?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Correo Electrónico</label>
                                <input tabindex="7" type="text" name="correo" class="form-control" placeholder="correo@proveedor.com" value="<?php echo $item['correo']?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Estatus</label>
                                <br>
                                <label><input type="checkbox" tabindex="8" class="minimal" name="activo" value="1" <?php echo $item['activo'] == 1 ? 'checked': ''?>> Activo</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Rol</label>
                                <select tabindex="9" class="form-control select2" name="id_rol" >
                                    <option value="">Selecciona uno</option>
                                    <?php
                                    foreach($roles as $c){
                                        echo '<option value="' . $c['id'] . '" ' . ($c['id'] == $item['id_rol'] ? 'selected' : '') . ' >' . $c['nombre'] . '</option>';
                                    }
                                    ?>
                                </select>
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
<script>
$(document).ready(function(){
    if (!$('#check-es-empleado').iCheck('update')[0].checked){
        $('.select-empleados').attr('disabled','disabled').select2({'disabled': true});
    }
    $('#check-es-empleado').on('ifChanged',function(){
        if ($(this).is(':checked')){
            $('.select-empleados').removeAttr('disabled').select2({'disabled': false});
        } else {
            $('.select-empleados').attr('disabled','disabled').select2({'disabled': true}).val(null).trigger('change');
        }
    })

    $('.toggle-pass').on('click',function(){
        var $input = $(this).closest('.input-group').find('input'), 
            $icon = $(this).find('i')

        if ($input.attr('type') == 'password'){
            $input.attr('type','text')
            $icon.removeClass('fa-eye-slash').addClass('fa-eye')
        } else {
            $input.attr('type','password')
            $icon.removeClass('fa-eye').addClass('fa-eye-slash')
        }
    })
})
</script>