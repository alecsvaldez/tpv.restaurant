<?php
defined('_PUBLIC_ACCESS') or die();
include_once 'config.php';
?>
<section class="content tpv-content pb-5" ng-app="app" ng-controller="<?php echo $_GET['page']?>">
    <div class="flex">
        <div class="flex-child flex-1">
            <!-- Ordenes corriendo -->
            <div class="box box-primary mb-5"> 
                <div class="box-body">
                    <p class="text-primary text-center"><b>Comandas</b></p>
                    <div class="order-list">
                        <div class="tabs-comandas">
                            <ul class="nav nav-pills nav-stacked" id="ul-comandas">
                                <li class="" ng-class="{'active': comanda.activa == 1}">
                                    <a ng-click="nuevaComanda()">Nueva</a>
                                </li>
                                <li ng-repeat="c in comandas" ng-class="{'active': c.activa == 1}"><a ng-click="setComanda(c)">{{ c.comanda }}</a></li>
                            <ul>
                        </div>                  
                    </div>
                </div>
            </div>
        </div>
        <div class="flex-child flex-5">
            <!-- order list -->
            <div class="box box-primary mb-5"> 
                <div class="box-body "> 
                    <div class="table-scroll mt-10">
                        <table class="table-striped sale_cart" style="width: 100%;">
                            <thead>
                            <tr>
                                <th style="min-width: 28%;">Prod.</th>
                                <th style="width: 18%;">Precio</th>
                                <th style="width: 13%;">Cant.</th>
                                <th ng-if="enable_product_discount" style="width: 22%;">Desc/%</th>
                                <th style="width: 25%;">Total</th>
                                <th style="text-align: center; width: 10%;"><i class="fa fa-trash"></i></th>
                            </tr>
                            </thead>
                            <tbody id="lpc">
                                <tr ng-repeat="p in comanda.productos" ng-click="openProduct(p)" ng-class="{'text-warning': p.en_preparacion}">
                                    <td>{{ p.nombre }} 
                                        <i class="fa fa-comment text-primary" ng-if="p.comentarios.length > 0"></i> 
                                        <i class="fa fa-fire text-danger" ng-if="p.en_preparacion == 1"></i> 
                                    </td>
                                    <td><input type="text" readonly class="input-precio numeric" ng-model="p.precio"/></td>
                                    <td><input type="text" class="input-precio numeric-filter" min="1" ng-readonly="p.en_preparacion == 1" ng-model="p.cantidad" onfocus="this.select()" ng-keyup="calculateRow(p)" /></td>
                                    <td ng-if="enable_product_discount"><input type="text" disabled class="input-precio numeric-filter discount" maxlength="6" ng-model="p.descuento" /></td>                
                                    <td><input type="text" tabindex="-1" class="input-precio input-total" readonly ng-model="p.total"></td>
                                    <td style="text-align: center"><a tabindex="-1" ng-if="p.en_preparacion != 1" class="btn btn-danger btn-xs" ng-click="deleter(p, $index);$event.stopPropagation()"><i style="color:white" class="fa fa-trash"></i></a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="calculation-area">
                        <!-- <p>
                            <span class="fl-width">Productos: </span>
                            <span class="sl-width" >{{comanda.count_items}}</span>
                            <span class="tl-width">Subtotal:</span>
                            <span class="fil-width">
                            <input type="text" tabindex="-1" value="0.00" readonly name="subtotal" id="subtotal" ng-model="comanda.subtotal"></span>
                        </p>
                        <p>
                            <span class="fl-width">Descuento/%:</span>
                            <span class="sl-width">
                                <input type="text" maxlength="6" onfocus="this.select()" ng-model="comanda.descuento_str" value=""  name="descuento" id="descuento" class="discount" autocomplete="off" ng-keyup="checkDiscount();" ng-disabled="!enable_total_discount">
                            </span>
                            <span class="tl-width">Total Desc:</span>
                            <span class="fil-width">
                                <input type="text" tabindex="-1" ng-model="comanda.descuento" value="0.00" readonly name="descuento_total" id="descuento_total">
                            </span>
                        </p>
                        <p>
                            <span class="fl-width"></span>
                            <span class="sl-width"></span>
                            <span class="tl-width">
                                <input type="checkbox" name="con_iva" id="con_iva" value="1" >
                                IVA:
                            </span>
                            <span class="fil-width">
                                <input type="text" tabindex="-1" class="disabled" value="0.00" readonly name="iva" id="iva" ng-model="comanda.iva">
                            </span>
                        </p> -->
                        <hr class="border-top-pay">
                        <p>
                            <span class="fl-width"></span>
                            <span class="sl-width"></span>
                            <span class="tl-width" style="vertical-align: super"><b>TOTAL</b></span>
                            <span class="fil-width">
                                <span class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" tabindex="-1" value="0.00" ng-model="comanda.total" name="total_a_pagar" readonly id="total_a_pagar">
                                </span>
                            </span>
                        </p>
                    </div>
                    <div class="btn-area " style="margin-bottom: 9px">
                        <div class="btn-group-custom">
                            <!-- <button type="button" class="btn btn-danger" id="btn-cancel" ng-disabled="comanda.productos.length == 0" ng-click="cancelarComanda(comanda)" style="width: 24%">Cancelar</button> -->
                            <!-- <button type="button" class="btn btn-warning" id="btn-hold" ng-disabled="comanda.productos.length == 0" style="width: 24%">En Espera</button> -->
                            <button type="button" class="btn btn-green" id="btn-ticket" ng-disabled="comanda.productos.length == 0" style="width: 20%"  ng-click="generarCuenta()">Cuenta</button>
                            <button type="button" class="btn btn-success" id="btn-place-order" ng-disabled="comanda.productos.length == 0" ng-click="colocarOrden()" style="width: 29%">Colocar Orden</button>
                        </div>
                    </div>
                </div>
            </div> 
        </div> 
        <div class="flex-child flex-6 m-0">
            <!-- menu products -->
            <div class="box box-primary"> 
                <div class="box-body "> 
                    <div class="row">
                        <div class="col-sm-4">
                            <select class="form-control" name="">
                            <?php
                            foreach($menus as $m){
                                ?>
                                <option value="<?php echo $m['id']?>"><?php echo $m['nombre']?></option>
                                <?php
                            }
                            ?>
                            </select>
                        </div>
                        <div class="col-sm-8">
                            <select class="form-control select2" id="mesa-comanda">
                                <option value="-1">Selecciona la mesa</option>
                                <option value="0">Venta de mostrador</option>
                            <?php
                            foreach($mesas as $m){
                                ?>
                                <option value="<?php echo $m['id']?>"><?php echo $m['nombre']?></option>
                                <?php
                            }
                            ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-20">
                        <div class="col-sm-12">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li ng-repeat="c in categorias" role="presentation" ng-class="{'active': c.active}"><a role="tab" data-toggle="tab" ng-click="setCategoria(c)">{{c.categoria}}</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane fade in active">
                            <div class="product-items">
                                <a  ng-repeat="p in categoria.productos" 
                                    ng-click="addProducto(p)"
                                    ng-style="{'background-image': 'linear-gradient(rgba(88, 147, 208, 0.4), rgba(255,255,255,0.4)), url(/uploads/productos/' + p.id_producto + '.jpg?v=' + random + ')'}"
                                    class="product-item">
                                    {{p.nombre}}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div> 

    </div> 
    <!-- Modal -->
    <div id="modal-producto" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span class="text-muted">{{producto.categoria}}</span> <i class="fa fa-angle-double-right text-muted"></i> {{ producto.nombre }}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <img ng-src="/uploads/productos/{{ producto.id_producto }}.jpg" width="100%">
                        </div>
                        <div class="col-sm-8">
                            <div class="">
                                <label class="control-label">Precio</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-fw fa-usd"></i></span>
                                    <input type="text" readonly class="form-control input-precio numeric" ng-model="producto.precio" onfocus="this.select()" ng-keyup="calculateRow(producto)" />
                                    <div class="input-group-btn">
                                        <button disabled class="btn btn-default"><i class="fa fa-fw fa-edit"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <label class="control-label">Cantidad</label>
                                <div class="input-group">
                                    <div class="input-group-btn">
                                        <button class="btn btn-default" ng-disabled="producto.cantidad <= 1" ng-click="removeQty(producto)"><i class="fa fa-fw fa-minus"></i></button>
                                    </div>
                                    <input type="text" class="form-control input-precio numeric-filter" min="1" ng-model="producto.cantidad"onfocus="this.select()" ng-keyup="calculateRow(producto)" />
                                    <div class="input-group-btn">
                                        <button class="btn btn-default" ng-click="addQty(producto)"><i class="fa fa-fw fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <label class="control-label">Total</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-fw fa-usd"></i></span>
                                    <input type="text" readonly class="form-control input-precio numeric" ng-model="producto.total" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="">
                                <label class="control-label">Notas adicionales</label>
                                <textarea class="form-control" ng-model="producto.comentarios"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- keyboard widget css & script (required) -->
<link href="<?php echo $site_assets?>plugins/keyboard/css/keyboard.min.css" rel="stylesheet">
<script src="<?php echo $site_assets?>plugins/keyboard/jquery.keyboard.js"></script>
<!-- keyboard extensions (optional) -->
<link href="<?php echo $site_assets?>plugins/keyboard/css/keyboard-basic.css" rel="stylesheet">
<script src="<?php echo $site_assets?>plugins/keyboard/jquery.keyboard.extension-typing.min.js"></script>
<!-- Fullscreen -->
<script src="<?php echo $site_assets?>plugins/fullscreen/jquery.fullscreen.js"></script>

<style>
    .content-wrapper { height: calc(100vh - 95px) !important; overflow: hidden; }
    .content-wrapper .content { height: 100%; padding-top: 10px;}
    .flex { display: flex; align-content: stretch; height: 100%;}
    .flex-child { margin-right: 10px; }
    .flex-child .box, .flex-child .box-body { height: 100%; }
    .flex-1 { flex: 1; }
    .flex-2 { flex: 2; }
    .flex-3 { flex: 3; }
    .flex-4 { flex: 4; }
    .flex-5 { flex: 5; }
    .flex-6 { flex: 6; }
    .flex-7 { flex: 7; }
    .flex-8 { flex: 8; }
    .tab-content { overflow-y: auto; height: calc(100% - 95px); }

    .tpv-content .nav > li > a { padding: 8px 10px !important; }
    #btn-usuario-tpv {background: #00a65a !important}
    .input-pin {font-size: 48px;text-align: center;letter-spacing: 16px;padding: 0; margin: 10px 0; }
    .calculation-area input { text-align: right;}
    .calculation-area .input-group-addon { padding: 1px 12px !important; }
    input.disabled { color: #afafaf; font-weight: normal; }
    input[readonly],
    input[readonly="readonly"] {
        background: #f3f3f3;
        border: 1px solid #e2e2e2;
        padding: 0px 5px;
    }
    .order-list { height: calc(100% - 30px); overflow-y: auto;}
    .table-scroll {
        margin: 0 0 5px;
        overflow: auto;
        height: calc(100% - 90px)
    }
    .sale_cart td { padding: 6px 4px; font-size:12px;  }
    .sale_cart .input-precio { width: 100%; padding: 1px 6px; text-align: right}

    .calculation-area > p {
        padding: 0;
        width: 100%;
        color: #000;
    }

    .calculation-area input {
        width: 100%;
    }
    .border-top-pay {
        background: #000 none repeat scroll 0 0;
        border: 1px solid #000;
        margin: 0;
        margin-bottom: 8px;
    }
    /* TPV */
    .product-list {
        width: 100%;
        height: 429px;
        overflow: auto;
    }
    a.product-item {
        text-decoration: none;
        background: #0c5889 none repeat scroll 0 0;
        background-size: cover;
        background-position: 50%;
        border-radius: 5px;
        color: #fff;
        text-shadow: 1px 1px 8px #0C5889;
        float: left;
        font-weight: 700;
        margin: 5px 10px;
        text-align: center;
        width: 15.5%;
        height: 90px;
        border: medium none;
        padding: 6px 0 26px 0;
        box-shadow: 0px 2px 3px 0px rgb(136, 136, 136)
    }
    .ui-keyboard[data-ui-keyboard-layout="custom"]
    .ui-keyboard-actionkey:not(.ui-keyboard-dec):not(.ui-keyboard-combo) {
        min-width: 3em;
    }
    .ui-keyboard[data-ui-keyboard-layout="custom"]
    .ui-keyboard-space {
        width: 15em;
    }
    .ui-keyboard[data-ui-keyboard-layout="custom"]
    .ui-keyboard-actionkey:not(.ui-keyboard-dec):not(.ui-keyboard-combo) span {
        font-size: 0.8em;
        position: relative;
        top: 0em;
        left: 0em;
    }
    .ui-keyboard-bksp { 
        color: #fff;
        background: #286090 !important;
        border-color: #204d74;
    }
    .btn-action[data-name="cancel"] { 
        color: #fff;
        background: #c9302c !important;
        border-color: #ac2925;
    }
    .btn-action[data-name="enviar"] { 
        color: #fff;
        background: #449d44 !important;
        border-color: #398439;
    }
    .ui-keyboard[data-ui-keyboard-layout="custom"] .ui-keyboard-button {
        font-size: 24px;
    }

</style>