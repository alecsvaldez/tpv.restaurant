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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nombre del Ingrediente <span class="required_star">*</span></label>
                                <input tabindex="1" type="text" name="nombre" class="form-control" placeholder="Nombre del ingrediente" 
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Categoría</label>
                                <select tabindex="2" class="form-control select2" name="id_categoria" style="width: 100%;">
                                    <option value="">Selecciona uno</option>
                                    <?php
                                    foreach($categorias as $c){
                                        echo '<option value="' . $c['id'] . '" ' . ($c['id'] == $item['id_categoria'] ? 'selected' : '') . ' >' . $c['nombre'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Descripción</label>
                                <input tabindex="2" type="text" name="descripcion" class="form-control" placeholder="Descripción" 
                                value="<?php echo $item['descripcion']?>">
                            </div>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Unidad de Medida Base</label>
                                <select tabindex="2" class="form-control select2" name="id_unidad" style="width: 100%;">
                                    <option value="">Selecciona uno</option>
                                    <?php
                                    foreach($tipos_medida as $r){
                                        echo '<option value="' . $r['id'] . '" ' . ($r['id'] == $item['id_unidad'] ? 'selected' : '') . ' >' . $r['nombre'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Unidad de Entrada</label>
                                <select tabindex="2" class="form-control select2" name="id_unidad_entrada" style="width: 100%;">
                                    <option value="">Selecciona uno</option>
                                    <?php
                                    foreach($tipos_medida as $r){
                                        echo '<option value="' . $r['id'] . '" ' . ($r['id'] == $item['id_unidad_entrada'] ? 'selected' : '') . ' >' . $r['nombre'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Factor Conversión</label>
                                <input type="text" name="factor_conversion" class="form-control" placeholder="Factor Conversión" 
                                value="<?php echo $item['factor_conversion']?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Unidad de Medida Salida</label>
                                <select tabindex="2" class="form-control select2" name="id_unidad_salida" style="width: 100%;">
                                    <option value="">Selecciona uno</option>
                                    <?php
                                    foreach($tipos_medida as $r){
                                        echo '<option value="' . $r['id'] . '" ' . ($r['id'] == $item['id_unidad_salida'] ? 'selected' : '') . ' >' . $r['nombre'] . '</option>';
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