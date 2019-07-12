<?php
defined('_PUBLIC_ACCESS') or die();
include_once 'config.php';

$editor_error = (!empty($_SESSION['db_message'])) ? $_SESSION['db_message']['submessage'] : false;
unset($_SESSION['db_message']);
if ($id > 0){
    $item = $db->find($table,$columns_item, $conditions_item);

    $item['items'] = $db->select('tb_compras_detalle',array(
        'id_registro' => 'id',
        'id_item' => 'IdItem',
        'id_unidad' => 'IdUnidad',
        'id_unidad_original' => 'IdUnidadOriginal',
        'precio' => 'Precio',
        'cantidad' => 'Cantidad',
        'total' => 'Total',
    ), array(
        'IdCompra = ' . $id
    ));
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
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Folio / No. de Compra <span class="required_star">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon" style="padding-right: 2px;"><?php echo sprintf('%05d', $item['id'])?> -</span>
                                    <input tabindex="1" type="text" name="folio" class="form-control" placeholder="Folio de Compra" value="<?php echo $item['folio']?>" required>
                                </div>
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
                            <div class="form-group">
                                <label>Proveedor</label>
                                <select tabindex="2" class="form-control select2" name="id_proveedor" style="width:100%">
                                    <option value="0">Ningún proveedor seleccionado</option>
                                <?php
                                foreach($proveedores as $p){
                                    echo '<option value="' . $p['id'] .'" ' . ($p['id'] == $item['id_proveedor'] ? 'selected' : '') . ' >' . $p['nombre'] . '</option>';
                                }
                                ?>
                                </select>
                            </div>
                        </div> 
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Fecha de Compra</label>
                                <input tabindex="3" type="text" name="fecha_compra" class="form-control datepicker" placeholder="YYYY-MM-DD" value="<?php echo $item['fecha_compra']?>" required>
                            </div>
                        </div> 
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Fecha de Entrega</label>
                                <input tabindex="4" type="text" name="fecha_entrega" class="form-control datepicker" placeholder="YYYY-MM-DD" value="<?php echo $item['fecha_entrega']?>">
                            </div>
                        </div> 
                    </div>
                    <div class="row">
                        <!-- <div class="col-md-3">
                            <div class="form-group">
                                <label>Tipo de Compra  <span class="required_star">*</span></label>
                                <input tabindex="3" type="text" name="contacto" class="form-control" placeholder="Nombre del contacto" value="<?php echo $item['contacto']?>" required>
                            </div>
                        </div> -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Ingrediente</label>
                                <div class="input-group">
                                    <select tabindex="5" class="form-control select2 select-item" data-url="ingredientes/detalle/" style="width:100%">
                                        <option value="">Selecciona uno</option>
                                    <?php
                                    foreach($ingredientes as $p){
                                        echo '<option value="' . $p['id'] .'" >' . $p['nombre'] . '</option>';
                                    }
                                    ?>
                                    </select>
                                    <span class="input-group-btn" >
                                        <button type="button" class="btn btn-primary btn-add"><i class="fa fa-plus"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row text-center bg-primary m-0 pt-5 pb-5">
                        <div class="col-xs-5">Ingrediente</div>
                        <div class="col-xs-2">Precio 1X</div>
                        <div class="col-xs-2">Cantidad</div>
                        <div class="col-xs-2">Precio Total</div>
                        <div class="col-xs-1"></div>
                    </div>
                    <div class="row m-0" id="lista-compras">
                    <?php
                    foreach($item['items'] as $i){
                        ?>
                        <div class="row row-ingrediente m-0">
                            <input type="hidden" name="items[<?php echo $i['id_registro']?>][id_registro]" value="<?php echo $i['id_registro']?>">
                            <input type="hidden" name="items[<?php echo $i['id_registro']?>][id_item]" value="<?php echo $i['id_item']?>">
                            <input type="hidden" name="items[<?php echo $i['id_registro']?>][id_unidad_original]" value="<?php echo $i['id_unidad_original']?>">
                            <input type="hidden" name="items[<?php echo $i['id_registro']?>][id_unidad]" value="<?php echo $i['id_unidad']?>">
                            <div class="col-xs-5"><?php echo idToString($i['id_item'], $ingredientes_indexed)?><br><small class="text-muted"></small></div>
                            <div class="col-xs-2">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control precio" name="items[<?php echo $i['id_registro']?>][precio]" value="<?php echo $i['precio']?>"/>
                                </div>
                            </div>
                            <div class="col-xs-2">
                                <div class="input-group">
                                    <input type="text" class="form-control cantidad" name="items[<?php echo $i['id_registro']?>][cantidad]" value="<?php echo $i['cantidad']?>"/>
                                    <span class="input-group-addon"><?php echo idToString($i['id_unidad'], $unidades_indexed)?></span>
                                </div>
                            </div>
                            <div class="col-xs-2">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control total" name="items[<?php echo $i['id_registro']?>][total]" value="<?php echo $i['total']?>"/>
                                </div>
                            </div>
                            <div class="col-xs-1"><a class="btn btn-danger btn-xs" style="margin-left: 5px; margin-top: 10px;" onclick="deleter();"><i class="fa fa-trash"></i> </a></div>
                        </div>
                        <?php
                    }
                    ?>
                    </div>
                    <div class="row mt-10">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Cerrar Orden</label>
                                <br>
                                <label><input type="checkbox" class="minimal" id="check-orden-cerrada" name="orden_cerrada" value="1" <?php echo $item['orden_cerrada'] == 1 ? 'checked': ''?>>  Orden Cerrada</label>
                                <small class="help-block">Al cerrar una orden ya no se pueden agregar elementos ni modificar precios o cantidades.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Observaciones / Comentarios / Notas</label>
                                <textarea class="form-control" name="comentarios"><?php echo $item['comentarios']?></textarea>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Total A Pagar</label>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control saldo-total" name="saldo_total" value="<?php echo $item['saldo_total']?>" readonly>
                                </div>
                                <label>Saldo A Cuenta</label>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control pagado" name="saldo_pagado" value="<?php echo $item['saldo_pagado']?>" required>
                                </div>
                                <label>Saldo Pendiente</label>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control pendiente" name="saldo_pendiente" value="<?php echo $item['saldo_pendiente']?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" name="submit" value="submit" class="btn btn-primary">Guardar</button>
                    <a href="<?php echo $site_url?><?php echo $seccion?>"><button type="button" class="btn btn-primary">Regresar</button></a>
                </div>
                </form>            
            </div>
        </div>
    </div>
</section>
<script>
function orderClose(){
    $('.btn-add').attr('disabled','disabled')
    $('.select-item').attr('disabled','disabled').select2({'disabled': true}).val(null).trigger('change');
    $('.precio, .cantidad, .total, .pagado').attr('readonly','readonly')
}
$(document).ready(function(){
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
                    var list = $('#lista-compras'), index = Math.random() * -1
                    if (response){
                        var div = $('<div class="row row-ingrediente m-0">' + 
                            '<input type="hidden" name="items[' + index + '][id_registro]" value="0">' +
                            '<input type="hidden" name="items[' + index + '][id_item]" value="' + response.id + '">' +
                            '<input type="hidden" name="items[' + index + '][id_unidad_original]" value="' + response.id_unidad + '">' +
                            '<input type="hidden" name="items[' + index + '][id_unidad]" value="' + response.id_unidad + '">' +
                            '<div class="col-xs-5">' + response.nombre + '<br><small class="text-muted">' + response.categoria + '</small></div>' +
                            '<div class="col-xs-2"><div class="input-group"><span class="input-group-addon">$</span><input type="text" class="form-control precio" name="items[' + index + '][precio]"/></div></div>' +
                            '<div class="col-xs-2"><div class="input-group"><input type="text" class="form-control cantidad" name="items[' + index + '][cantidad]"/><span class="input-group-addon">' + response.unidad + '</span></div></div>' +
                            '<div class="col-xs-2"><div class="input-group"><span class="input-group-addon">$</span><input type="text" class="form-control total" name="items[' + index + '][total]"/></div></div>' +
                            '<div class="col-xs-1"><a class="btn btn-danger btn-xs" style="margin-left: 5px; margin-top: 10px;" onclick="deleter();"><i class="fa fa-trash"></i> </a></div>' +
                        '</div>');
                        list.append(div)
                    } else {
                        
                    }
                }
            })            
        }
    })
    $('#check-orden-cerrada').on('ifChanged',function(){
        if ($(this).is(':checked')){
            orderClose()
        } else {
            $('.select-item').removeAttr('disabled').select2({'disabled': false});
            $('.btn-add').removeAttr('disabled')
            $('.precio, .cantidad, .total, .pagado').removeAttr('readonly')
        }
    })

    $(document).on('blur','.precio, .cantidad',function(){
        var fila = $(this).closest('.row-ingrediente');
        sumaFila(fila)
        sumaTotal()
    })
    $(document).on('blur','.pagado',function(){
        saldos()
    })

    function sumaFila(fila){
        var precio = fila.find('.precio').val() || 0,
            cantidad = fila.find('.cantidad').val() || 0,
            total = 0
        total = precio * cantidad
        fila.find('.total').val(total)
    }
    function sumaTotal(){
        var total = 0;
        $('.total').each(function(k,i){
            total += parseFloat( $(i).val())
        })
        $('.saldo-total').val(total)
    }
    function saldos(){
        var total = $('.saldo-total').val(),
            pagado =  $('.pagado').val() || 0
        if (total == 0 && pagado > 0){
            alert('No se puede tener saldo pagado si el total a pagar es $ 0');
            return
        }
        $('.pendiente').val( total - pagado )
    }

    <?php
    if ($item['orden_cerrada'] == 1) echo ' orderClose(); ';
    ?>

})

</script>
<style>
.row-ingrediente {
    padding: 4px 0;
    border-bottom: 1px solid #efefef;
    clear: both;
}
</style>