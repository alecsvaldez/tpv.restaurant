<?php
defined('_PUBLIC_ACCESS') or die();
include_once 'config.php';

?>
<div class="" ng-app="app" ng-controller="<?php echo $_GET['page'] ?>">
    <div class="row">
        <div ng-repeat="c in comandas" class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
            <div class="single_order m-10">
                <div class="header_portion btn-primary active fix">
                    <div class="fix pull-left" style="width:70%;">
                        <p class="order_number">{{c.comanda}}</p>
                        <p class="order_number"><i class="fa fa-cutlery"></i>: {{ c.mesa }}</p>
                    </div>
                    <div class="fix pull-left" style="width:30%;">
                        <p class="order_duration dark-blue-background text-small">
                            <span am-time-ago="c.generada"></span>
                        </p>
                    </div>
                </div>
                <div class="slimScrollDiv">
                    <div class="fix items_holder">
                        <div ng-repeat="p in c.productos" class="single_item">
                            <div class="item_detail">
                                <p class="item_name" style=""><i ng-if="p.selected" class="fa fa-check-circle"></i> &nbsp; {{ p.nombre }}</p>
                                <p class="item_qty" style="font-weight:bold;">Cant: {{ p.cantidad }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-5 b-t">
                    <div class="calculation-area">
                        <p>
                            <span class="fl-width">Productos: </span>
                            <span class="sl-width">{{c.count_items}}</span>
                            <span class="tl-width">Subtotal:</span>
                            <span class="fil-width">
                                <input type="text" tabindex="-1" value="0.00" readonly name="subtotal" ng-model="c.subtotal"></span>
                        </p>
                        <!-- <p>
                            <span class="fl-width">Dcto/%:</span>
                            <span class="sl-width">
                                <input type="text" maxlength="6" onfocus="this.select()" ng-model="c.descuento_str" value=""  name="descuento" id="descuento" class="discount" autocomplete="off" ng-keyup="checkDiscount(c);">
                            </span>
                            <span class="tl-width">Total Desc:</span>
                            <span class="fil-width">
                                <input type="text" tabindex="-1" ng-model="c.descuento" value="0.00" readonly name="descuento_total" id="descuento_total">
                            </span>
                        </p> -->
                        <p>
                            <span class="fl-width"></span>
                            <span class="sl-width"></span>
                            <span class="tl-width p-0">
                                <label class="label-checkbox" ng-class="{'checked': c.iva_desglosado}">
                                    <input type="checkbox" class="check-iva" data-id-comanda="{{c.id_registro}}" name="con_iva" id="con_iva_{{ c.id_registro }}" value="1" ng-model="c.iva_desglosado" ng-true-value="1" ng-false-value="0" ng-checked="c.iva_desglosado == 1" ng-change="calculateTotal(c)">
                                    IVA:
                                </label>
                            </span>
                            <span class="fil-width">
                                <input type="text" tabindex="-1" class="disabled" value="0.00" readonly name="iva" ng-model="c.iva">
                            </span>
                        </p>
                        <hr class="border-top-pay">
                        <p>
                            <span class="fl-width"></span>
                            <span class="sl-width"></span>
                            <span class="tl-width" style="vertical-align: super"><b>TOTAL</b></span>
                            <span class="fil-width">
                                <span class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" tabindex="-1" value="0.00" ng-model="c.total" name="total_a_pagar" readonly>
                                </span>
                            </span>
                        </p>
                    </div>
                </div>
                <div class="btn-group btn-group-sm  btn-group-justified" role="group">
                    <button type="button" class="btn btn-danger" ng-click="cancelarCuenta(c)" style="width: 25%">Cancelar</button>
                    <button type="button" class="btn btn-info" ng-click="regresarComanda(c)" style="width: 45%">Regresar Comanda</button>
                    <button type="button" class="btn btn-success" ng-click="openCaja(c)" style="width: 30%">Cobrar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div id="modal-cobrar" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <form name="FormCaja" submit-validate ng-submit="cobrar(comanda)" novalidate>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><span class="text-muted">{{comanda.comanda}}</span> <i class="fa fa-angle-double-right text-muted"></i> {{ comanda.nombre }}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="box box-primary mb-5">
                            <div class="box-body ">
                                <div class="table-scroll mt-10 mb-5 b-b">
                                    <table class="table-striped sale_cart" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th style="min-width: 28%;">Prod.</th>
                                                <th style="width: 18%;">Precio</th>
                                                <th style="width: 13%;">Cant.</th>
                                                <th ng-if="enable_product_discount" style="width: 22%;">Desc/%</th>
                                                <th style="width: 15%; text-align: right">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody id="lpc">
                                            <tr ng-repeat="p in comanda.productos">
                                                <td>{{ p.nombre }}</td>
                                                <td>$ {{ p.precio }}</td>
                                                <td>{{ p.cantidad }}</td>
                                                <td ng-if="enable_product_discount">{{ p.descuento }}</td>
                                                <td align="right">$ {{ p.total }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="calculation-area">
                                    <p>
                                        <span class="fl-width">Productos: </span>
                                        <span class="sl-width">{{comanda.count_items}}</span>
                                        <span class="tl-width">Consumo:</span>
                                        <span class="fil-width text-right">$ {{ comanda.subtotal}}</span>
                                    </p>
                                    <p>
                                        <span class="fl-width">Descuento/%:</span>
                                        <span class="sl-width">{{ comanda.descuento_str }}</span>
                                        <span class="tl-width">Total Desc:</span>
                                        <span class="fil-width text-right">$ {{ comanda.descuento }}</span>
                                    </p>
                                    <p>
                                        <span class="fl-width"></span>
                                        <span class="sl-width"></span>
                                        <span class="tl-width">Subtotal:</span>
                                        <span class="fil-width text-right">$ {{ comanda.subtotal }}</span>
                                    </p>
                                    <p>
                                        <span class="fl-width"></span>
                                        <span class="sl-width"></span>
                                        <span class="tl-width">
                                            <label class="label-checkbox" ng-class="{'checked': comanda.iva_desglosado}">
                                                <input type="checkbox" class="check-iva natural" name="con_iva" ng-true-value="1" ng-false-value="0" ng-model="comanda.iva_desglosado" ng-checked="comanda.iva_desglosado == 1" ng-change="calculateTotal(comanda)">
                                                IVA:
                                            </label>
                                        </span>
                                        <span class="fil-width text-right">$ {{ comanda.iva }}</span>
                                    </p>
                                    <hr class="border-top-pay">
                                    <p>
                                        <span class="fl-width"></span>
                                        <span class="sl-width"></span>
                                        <span class="tl-width text-lg" style="vertical-align: super"><b>TOTAL A PAGAR</b></span>
                                        <span class="fil-width text-right text-lg"><b>$ {{ comanda.total }}<b></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- payment -->
                        <div class="">
                            <label class="control-label"><b>Forma de pago</b></label>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label"><b>Importe en Tarjeta</b></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                            <input class="form-control" ng-model="comanda.tarjeta" ng-change="calculaCambio()" ng-enter="cobrar(comanda)" />
                                        </div>
                                    </div>
                                    <div class="form-group" ng-if="comanda.tarjeta" ng-class="{'has-error': FormCaja.Banco.$invalid || FormCaja.TarjetaNum.$invalid} ">
                                        <label class="control-label"><b>Datos de Tarjeta</b></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-bank"></i></span>
                                            <input type="text" name="Banco" class="form-control" ng-model="comanda.tarjeta_banco" placeholder="Banco" ng-enter="cobrar(comanda)" required />
                                            <span class="input-group-addon"><i class="fa fa-hashtag"></i></span>
                                            <input type="text" name="TarjetaNum" class="form-control" ng-model="comanda.tarjeta_num" placeholder="0000" ng-enter="cobrar(comanda)" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label"><b>Efectivo Recibido</b></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                            <input class="form-control" name="efectivo" ng-model="comanda.efectivo" ng-change="calculaCambio()" ng-enter="cobrar(comanda)" focus />
                                        </div>
                                    </div>
                                    <div class="form-group" ng-if="comanda.cambio > 0">
                                        <label class="control-label"><b>Cambio</b></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                            <input class="form-control" ng-model="comanda.cambio" readonly />
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" id="btn-place-order" ng-disabled="comanda.productos.length == 0" style=" width: 29%">Cobrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- keyboard widget css & script (required) -->
<link href="<?php echo $site_assets ?>plugins/keyboard/css/keyboard.min.css" rel="stylesheet">
<script src="<?php echo $site_assets ?>plugins/keyboard/jquery.keyboard.js"></script>
<!-- keyboard extensions (optional) -->
<link href="<?php echo $site_assets ?>plugins/keyboard/css/keyboard-basic.css" rel="stylesheet">
<script src="<?php echo $site_assets ?>plugins/keyboard/jquery.keyboard.extension-typing.min.js"></script>

<script>
    $(document).ready(function() {
        $('body').addClass('sidebar-collapse');
    })
</script>
<style>

    .single_order {
        border-radius: 5px;
        background: #fff;
        position: relative;
        height: 500px;
    }

    .items_holder {
        height: 260px;
        overflow-y: auto
    }

    .single_item {
        display: flex
    }

    .single_item .item_detail {
        width: 100%;
        padding: 0px 10px;
    }

    .items_holder .single_item {
        padding: 8px 0px;
        cursor: pointer;
    }

    .single_order .header_portion {
        color: #fff;
    }

    .single_order .header_portion p.order_number {
        margin: 8px 10px;
        line-height: 14px;
    }

    .header_portion.light-blue-background {
        background-color: #0c5889;
    }

    .single_order .header_portion p.order_duration {
        display: inline-block;
        padding: 0px 9px;
        line-height: 26px;
        margin: 5px 7px;
        float: right;
        border-radius: 6px;
        font-size: 12px;
    }

    .btn-group .btn {
        border-radius: 0;
    }


    .calculation-area input {
        text-align: right;
    }

    .calculation-area .input-group-addon {
        padding: 1px 12px !important;
    }

    .calculation-area>p {
        padding: 0;
        width: 100%;
        color: #000;
    }

    .calculation-area input[type="text"] {
        width: 100%;
    }

    input.disabled {
        color: #afafaf;
        font-weight: normal;
    }

    input[readonly],
    input[readonly="readonly"] {
        background: #f3f3f3;
        border: 1px solid #e2e2e2;
        padding: 0px 5px;
    }

    .border-top-pay {
        background: #000 none repeat scroll 0 0;
        border: 1px solid #000;
        margin: 0;
        margin-bottom: 8px;
    }

    .sale_cart td {
        padding: 6px 4px;
        font-size: 12px;
    }

    .sale_cart .input-precio {
        width: 100%;
        padding: 1px 6px;
        text-align: right
    }

    /* */
    .input-pin {
        font-size: 48px;
        text-align: center;
        letter-spacing: 16px;
        padding: 0;
        margin: 10px 0;
    }

    .ui-keyboard[data-ui-keyboard-layout="custom"] .ui-keyboard-actionkey:not(.ui-keyboard-dec):not(.ui-keyboard-combo) {
        min-width: 3em;
    }

    .ui-keyboard[data-ui-keyboard-layout="custom"] .ui-keyboard-space {
        width: 15em;
    }

    .ui-keyboard[data-ui-keyboard-layout="custom"] .ui-keyboard-actionkey:not(.ui-keyboard-dec):not(.ui-keyboard-combo) span {
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