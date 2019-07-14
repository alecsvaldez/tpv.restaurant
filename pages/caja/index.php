<?php
defined('_PUBLIC_ACCESS') or die();
include_once 'config.php';

?>
<div class=""  ng-app="app" ng-controller="<?php echo $_GET['page']?>">
    <div class="row">
        <div ng-repeat="c in comandas" class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
            <div class="single_order m-10">
                <div class="header_portion btn-primary active fix">
                    <div class="fix pull-left" style="width:70%;">
                        <p class="order_number">{{c.comanda}}</p>
                        <p class="order_number"><i class="fa fa-cutlery"></i>: N/D</p>
                    </div>
                    <div class="fix pull-left" style="width:30%;">
                        <p class="order_duration dark-blue-background text-small">
                            <span am-time-ago="c.generada"></span>
                        </p>
                    </div>
                </div>
                <div class="slimScrollDiv">
                    <div class="fix items_holder">
                        <div ng-repeat="p in c.productos" class="single_item" >
                            <div class="item_detail">
                                <p class="item_name" style=""><i ng-if="p.selected" class="fa fa-check-circle"></i> &nbsp;  {{ p.nombre }}</p>
                                <p class="item_qty" style="font-weight:bold;">Cant: {{ p.cantidad }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-5 b-t">
                    <div class="calculation-area">
                        <p>
                            <span class="fl-width">Productos: </span>
                            <span class="sl-width" >{{c.count_items}}</span>
                            <span class="tl-width">Subtotal:</span>
                            <span class="fil-width">
                            <input type="text" tabindex="-1" value="0.00" readonly name="subtotal" id="subtotal" ng-model="c.subtotal"></span>
                        </p>
                        <p>
                            <span class="fl-width">Dcto/%:</span>
                            <span class="sl-width">
                                <input type="text" maxlength="6" onfocus="this.select()" ng-model="c.descuento_str" value=""  name="descuento" id="descuento" class="discount" autocomplete="off" ng-keyup="checkDiscount(c);">
                            </span>
                            <span class="tl-width">Total Desc:</span>
                            <span class="fil-width">
                                <input type="text" tabindex="-1" ng-model="c.descuento" value="0.00" readonly name="descuento_total" id="descuento_total">
                            </span>
                        </p>
                        <p>
                            <span class="fl-width"></span>
                            <span class="sl-width"></span>
                            <span class="tl-width">
                                <input type="checkbox" class="check-iva" data-id-comanda="{{c.id_registro}}" name="con_iva" id="con_iva_{{ c.id_registro }}" value="1" 
                                    ng-model="c.iva_desglosado" 
                                    ng-true-value="1"
                                    ng-false-value="0" 
                                    ng-checked="c.iva_desglosado == 1"
                                    ng-change="calculateTotal(c)">
                                IVA:
                            </span>
                            <span class="fil-width">
                                <input type="text" tabindex="-1" class="disabled" value="0.00" readonly name="iva" id="iva" ng-model="c.iva">
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
                                    <input type="text" tabindex="-1" value="0.00" ng-model="c.total" name="total_a_pagar" readonly id="total_a_pagar">
                                </span>
                            </span>
                        </p>
                    </div>            
                </div>
                <div class="single_order_button_holder">
                    <button class="btn floatright" ng-click="openCaja(c)">Cobrar</button>
                </div>
            </div>
        </div>   
    </div>


    <!-- Modal -->
    <div id="modal-cobrar" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
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
                                    <span class="sl-width" >{{comanda.count_items}}</span>
                                    <span class="tl-width">Subtotal:</span>
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
                                    <span class="tl-width">IVA:</span>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success" id="btn-place-order" ng-disabled="comanda.productos.length == 0" ng-click="cobrar(comanda)" style="width: 29%">Cobrar</button>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function(){
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
    .items_holder { min-height: 260px; }
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
    .header_portion.light-blue-background{
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
    .single_order_button_holder {
        background: #cfcfcf;
        position: absolute;
        width: 100%;
        bottom: 0px;
        left: 0px;
    }

    .single_order_button_holder button {
        border: 0px solid #fff;
        box-shadow: none;
        background: #cfcfcf;
        border-radius: 0px;
        padding: 1px 4.5%;
        line-height: 34px;
        font-size: 11px;
        cursor: pointer;
    }
    .single_order_button_holder button:hover {
        background: #bbbbbb;
    }
    
    .calculation-area input { text-align: right;}
    .calculation-area .input-group-addon { padding: 1px 12px !important; }
    .calculation-area > p {
        padding: 0;
        width: 100%;
        color: #000;
    }
    .calculation-area input[type="text"] {
        width: 100%;
    }
    input.disabled { color: #afafaf; font-weight: normal; }
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

    .sale_cart td { padding: 6px 4px; font-size:12px;  }
    .sale_cart .input-precio { width: 100%; padding: 1px 6px; text-align: right}
</style>