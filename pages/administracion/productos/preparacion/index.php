<?php
defined('_PUBLIC_ACCESS') or die();
include_once 'config.php';

$editor_error = (!empty($_SESSION['db_message'])) ? $_SESSION['db_message']['submessage'] : false;
unset($_SESSION['db_message']);
?>
<section class="content-header">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="top-left-header"><?php echo $titulo_editor ?> <span class="text-primary"><?php echo $producto['nombre']?></span></h3>  
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
                    <input type="hidden" name="subseccion" value="">
                    <input type="hidden" name="action" value="<?php echo $subseccion?>">
                    <input type="hidden" name="id" value="<?php echo $item['id'] ?>">
                    <input type="hidden" name="id_producto" value="<?php echo $id_producto ?>">
                <div class="box-body">
                    <div class="row">
                    <div class="col-md-6">
                            <div class="form-group">
                                <label>Instrucciones Generales</label>
                                <textarea name="instrucciones" class="form-control" placeholder="Instrucciones Generales"
                                rows="10"><?php 
                                echo $item['instrucciones']?></textarea>
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
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label>Ingredientes <span class="required_star">*</span></label>
                                        <div class="input-group">
                                            <!-- <input type="text" name="ingrediente" class="form-control" placeholder="Buscar ingrediente" value=""> -->
                                            <select class="form-control select2" name="id_ingrediente" style="width: 100%;" data-url="ingredientes/detalle/">
                                                <option value="">Agregar Ingredientes</option>
                                                <?php
                                                foreach($ingredientes as $c){
                                                    echo '<option value="' . $c['id'] . '" ' . ($c['id'] == $item['id_categoria'] ? 'selected' : '') . ' >' . $c['nombre'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-primary btn-add"><i class="fa fa-plus"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 ">
                                    <div class="row bg-primary m-0 pt-5 pb-5">
                                        <div class="col-xs-5">Ingrediente</div>
                                        <div class="col-xs-3">Cantidad</div>
                                        <div class="col-xs-3">Unidad</div>
                                        <div class="col-xs-1"></div>
                                    </div>
                                </div>
                                <div class="col-xs-12" id="lista-ingredientes" >
                                <?php
                                foreach($item['ingredientes'] as $i){
                                    ?>
                                    <div class="row row-ingrediente m-0">
                                        <input type="hidden" class="input-id-registro" name="ingrediente[<?php echo $i['id']?>][id_registro]" value="<?php echo $i['id_ingrediente']?>">
                                        <div class="col-xs-5">
                                            <?php echo $ingredientes_indexed[$i['id_ingrediente']]?><br>
                                            <small class="text-muted"></small>
                                        </div>
                                        <div class="col-xs-3"><input type="text" class="form-control" name="ingrediente[<?php echo $i['id']?>][cantidad]" value="<?php echo $i['cantidad']?>"></div>
                                        <div class="col-xs-3"><input type="hidden" name="ingrediente[<?php echo $i['id']?>][unidad]" value="<?php echo $i['id_unidad']?>">
                                            <?php echo $unidades_indexed[$i['id_unidad']]?>
                                        </div>
                                        <div class="col-xs-1">
                                            <a class="btn btn-danger btn-xs delete-ingrediente" style="margin-left: 5px; margin-top: 10px;"><i class="fa fa-trash"></i> </a>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="row">
                        
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
jQuery(document).ready(function(){
    $('.btn-add').on('click',function(){
        var select = $(this).closest('.input-group').find('select')
        if (select.val() > 0){
            // Si hay una opcion seleccionada, vamos por el registro
            var url = '/ajax/' + select.attr('data-url') + select.val()
            select.val(null).trigger('change')
            $.ajax({
                method: 'GET',
                url: url,
                success: function(response){
                    var list = $('#lista-ingredientes')
                    if (response){
                        var div = $('<div class="row row-ingrediente m-0">' + 
                            '<input type="hidden" name="ingrediente[' + response.id_item + '][id_registro]" value="0">' +
                            '<div class="col-xs-5">' + response.item + '<br><small class="text-muted">' + response.categoria + '</small></div>' +
                            '<div class="col-xs-3"><input type="text" class="form-control" name="ingrediente[' + response.id_item + '][cantidad]"/></div>' +
                            '<div class="col-xs-3"><input type="hidden" name="ingrediente[' + response.id_item + '][unidad]" value="' + response.id_unidad_salida + '">' + response.unidad_salida + '</div>' +
                            '<div class="col-xs-1"><a class="btn btn-danger btn-xs" style="margin-left: 5px; margin-top: 10px;" onclick="deleter();"><i class="fa fa-trash"></i> </a></div>' +
                        '</div>');
                        list.append(div)
                    } else {
                        
                    }
                }
            })            
        }
    })
    $(document).on('click', '.delete-ingrediente', function(){
        let $fila = $(this).closest('.row-ingrediente')
        let id_registro = $fila.find('.input-id-registro').val()
        console.log(id_registro)
        if (id_registro > 0){
            // peticion ajax para borrar
            var url = '/ajax/productos/borrar-preparacion/' + id_registro
            $.ajax({
                method: 'POST',
                url: url,
                success: function(response){
                    $fila.remove()
                }
            })      
            
        } else {
            $fila.remove()
        }
    })
})
</script>
<style>
.row-ingrediente {
    padding: 4px 0;
    border-bottom: 1px solid #efefef;
}
</style>