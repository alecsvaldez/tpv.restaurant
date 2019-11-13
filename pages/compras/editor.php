<?php
defined('_PUBLIC_ACCESS') or die();
include_once 'config.php';

$editor_error = (!empty($_SESSION['db_message'])) ? $_SESSION['db_message']['submessage'] : false;
unset($_SESSION['db_message']);

?>
<section class="content-header">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="top-left-header"><?php echo $titulo_editor ?></h3>  
        </div>
    </div>
</section>
<section class="content" ng-app="app" ng-controller="<?php echo $_GET['seccion'] . '_' . $_GET['page'] ?>">
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary"> 
                <!-- form start -->
                <form action="./" method="post" accept-charset="utf-8" id="form" autocomplete="off">
                    <input type="hidden" name="seccion" value="<?php echo $seccion?>">
                    <input type="hidden" name="action" value="editor">
                    <input type="hidden" name="id" value="<?php echo $id ?>">
                    <input type="hidden" name="items">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Folio / No. de Compra <span class="required_star">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon" style="padding-right: 2px;">{{item.serial_id}} -</span>
                                    <input tabindex="1" type="text" name="folio" class="form-control" placeholder="Folio de Compra" ng-model="item.folio" value="" required>
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
                                <select tabindex="2" class="form-control select2" name="id_proveedor" style="width:100%" ng-model="item.id_proveedor">
                                    <option value="0">Ningún proveedor seleccionado</option>
                                <?php
                                foreach($proveedores as $p){
                                    echo '<option value="' . $p['id'] .'" ' . ' >' . $p['nombre'] . '</option>';
                                }
                                ?>
                                </select>
                            </div>
                        </div> 
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Fecha de Compra</label>
                                <input tabindex="3" type="text" name="fecha_compra" class="form-control datepicker" placeholder="YYYY-MM-DD" ng-model="item.fecha_compra" value="" required>
                            </div>
                        </div> 
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Fecha de Entrega</label>
                                <input tabindex="4" type="text" name="fecha_entrega" class="form-control datepicker" placeholder="YYYY-MM-DD" ng-model="item.fecha_entrega" value="">
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
                                <label>Productos</label>
                                <div class="input-group">
                                    <select tabindex="5" id="select-productos" class="form-control select2 select-item" data-url="ingredientes/detalle/" style="width:100%">
                                        <option value="">Selecciona uno</option>
                                    <?php
                                    foreach($ingredientes as $p){
                                        echo '<option value="' . $p['id'] .'" >' . $p['nombre'] . '</option>';
                                    }
                                    ?>
                                    </select>
                                    <span class="input-group-btn" >
                                        <button type="button" class="btn btn-primary" ng-click="addProduct()"><i class="fa fa-plus"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row text-center bg-primary m-0 pt-5 pb-5">
                        <div class="col-xs-5">Producto</div>
                        <div class="col-xs-2">Precio x1</div>
                        <div class="col-xs-2">Cantidad</div>
                        <div class="col-xs-2">Precio Total</div>
                        <div class="col-xs-1"></div>
                    </div>
                    <div class="row m-0" id="lista-compras">
                        <div class="row row-ingrediente m-0" ng-repeat="p in item.items">
                            <div class="col-xs-5">{{p.item}}<br><small class="text-muted">{{p.categoria}}</small></div>
                            <div class="col-xs-2">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control precio" ng-model="p.precio" ng-change="calculate(p)"/>
                                </div>
                                <small>1{{p.unidad_entrada}} = {{ (p.conversion) }} {{p.unidad_salida}}</small>
                            </div>
                            <div class="col-xs-2">
                                <div class="input-group">
                                    <input type="text" class="form-control cantidad" ng-model="p.cantidad" ng-change="calculate(p)"/>
                                    <span class="input-group-addon">{{p.unidad_entrada}}</span>
                                </div>
                                <small class="text-primary text-small">{{ p.conversion * (p.cantidad || 0) }} {{p.unidad_salida}}</small>
                            </div>
                            <div class="col-xs-2">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control total" ng-model="p.total" readonly tabindex="-1" />
                                </div>
                            </div>
                            <div class="col-xs-1"><a class="btn btn-danger btn-xs" style="margin-left: 5px; margin-top: 10px;" ng-click="deleter();" tabindex="-1"><i class="fa fa-trash"></i> </a></div>
                        </div>
                    </div>

                    <div class="row mt-10">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Cerrar Orden</label>
                                <br>
                                <label><input type="checkbox" icheck class="minimal" ng-model="item.orden_cerrada" id="check-orden-cerrada" name="orden_cerrada" value="1">  Orden Cerrada</label>
                                <small class="help-block">Al cerrar una orden ya no se pueden agregar elementos ni modificar precios o cantidades.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Observaciones / Comentarios / Notas</label>
                                <textarea class="form-control" name="comentarios" ng-model="item.comentarios"></textarea>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Total A Pagar</label>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control saldo-total" name="saldo_total" ng-model="item.saldo_total" value="" readonly>
                                </div>
                                <label>Saldo A Cuenta</label>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control pagado" name="saldo_pagado" ng-model="item.saldo_pagado" value="" required  ng-change="calculate(p)">
                                </div>
                                <label>Saldo Pendiente</label>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control pendiente" name="saldo_pendiente" ng-model="item.saldo_pendiente" value="" readonly>
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
var id = <?php echo $id ?>
</script>
<style>
.row-ingrediente {
    padding: 4px 0;
    border-bottom: 1px solid #efefef;
    clear: both;
}
</style>