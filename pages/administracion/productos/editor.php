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
                <form action="./" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <input type="hidden" name="seccion" value="<?php echo $seccion?>">
                    <input type="hidden" name="action" value="editor">
                    <input type="hidden" name="id" value="<?php echo $id ?>">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Producto <span class="required_star">*</span></label>
                                <input tabindex="1" type="text" name="nombre" class="form-control" placeholder="Nombre del producto" 
                                value="<?php echo $item['nombre']?>" required>
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
                        <div class="col-md-9">
                            <div class="form-group">
                                <label>Descripción</label>
                                <input tabindex="2" type="text" name="descripcion" class="form-control" placeholder="Descripción" 
                                value="<?php echo $item['descripcion']?>">
                                <small class="help-block">Es la descripción que aparecerá en el menú</small>
                            </div>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Categoría</label>
                                <select tabindex="3" class="form-control select2 select-dependant" name="id_categoria" style="width: 100%;"
                                    data-child="#tipo-producto" data-url="productos/tipos-producto/">
                                    <option value="">Selecciona uno</option>
                                    <?php
                                    foreach($categorias as $c){
                                        echo '<option value="' . $c['id'] . '" ' . ($c['id'] == $item['id_categoria'] ? 'selected' : '') . ' >' . $c['nombre'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tipo de Producto</label>
                                <select tabindex="4" class="form-control select2" id="tipo-producto" name="id_tipo_producto" style="width: 100%;">
                                    <option value="">Selecciona uno</option>
                                    <?php
                                    foreach($tipos_producto as $r){
                                        echo '<option value="' . $r['id'] . '" ' . ($r['id'] == $item['id_tipo_producto'] ? 'selected' : '') . ' >' . $r['nombre'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>  
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Costo Aproximado</label>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" name="costo" class="form-control" placeholder="0.00" value="<?php echo $item['costo']?>">
                                </div>
                                <small class="help-block">Costo aproximado de producción</small>
                            </div>
                        </div>                
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Precio de Venta</label>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" name="precio" class="form-control" placeholder="0.00" value="<?php echo $item['precio']?>">
                                </div>
                                <small class="help-block">Precio Base, puede ser modificado en cada menú</small>
                            </div>
                        </div>          
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <label class="control-label">Foto</label>
                            <br>                            
                            <button type="button" class="btn btn-primary" id="btn-trigger">Selecciona imagen</button>
                            <input type="file" id="input-file" name="foto" style="display:none">
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Información de la imagen</label>
                            <br>                            
                            <div class="foto-container">
                                <img id="foto-preview" src="<?php echo $foto ?>">
                            </div>
                            <div class="foto-info">
                                <b>Nombre:</b> <span id="foto-name"><?php echo $foto ? 'producto.jpg' : ''?></span>
                                <br>                                
                                <b>Tamaño:</b> <span id="foto-size"><?php echo $foto ? $foto_size : '' ?></span>
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
<style>
.foto-container{
    width: 100px;
    float: left;
}
#foto-preview {
    height: 100px;
    object-fit: cover;
}
.foto-info {
    font-size: 11px;
    margin-left: 110px;
}
</style>
<script>
$(document).ready(function(){
    $('#btn-trigger').click(function(){
        // var input = document.createElement('input')
        // input.type = 'file';
        var input = document.getElementById('input-file')
        $(input).trigger('click');        
        input.onchange = function(){
            if (input.files && input.files[0]){
                var reader = new FileReader()
                reader.onload = (e) => {
                    $('#foto-preview').attr('src',e.target.result)
                    $('#foto-name').text(input.files[0].name)
                    $('#foto-size').text( (input.files[0].size).fileSize() )
                }      
                reader.readAsDataURL(input.files[0]);
            }
        }
    })
})
</script>