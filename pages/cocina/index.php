<?php
defined('_PUBLIC_ACCESS') or die();
include_once 'config.php';

?>
<div class="fix main_bottom"  ng-app="app" ng-controller="<?php echo $_GET['page']?>">
    <div class="fix order_holder" id="order_holder">
        <div ng-repeat="c in comandas" class="fix floatleft single_order">
            <div class="header_portion btn-primary active fix">
                <div class="fix floatleft" style="width:70%;">
                    <p class="order_number">{{c.comanda}}</p>
                    <p class="order_number"><i class="fa fa-cutlery"></i>: N/D</p>
                </div>
                <div class="fix floatleft" style="width:30%;">
                    <p class="order_duration dark-blue-background text-small">
                        <span am-time-ago="c.generada"></span>
                    </p>
                </div>
            </div>
            <div class="slimScrollDiv">
                <div class="fix items_holder">
                    <div ng-repeat="p in c.productos" 
                        class="fix single_item" 
                        id="c-{{ c.id_registro }}-p-{{ p.id_registro }}" 
                        ng-class="{
                            'selected': p.selected,
                            'cooking': p.cooking,
                            'done': p.done,
                            'deliver': p.deliver,
                        }"
                        ng-click="toggleSelected(p)"
                        >
                        <div class="single_item_left_side fix">
                            <div class="fix floatleft item_detail">
                                <p class="item_name" style=""><i ng-if="p.selected" class="fa fa-check-circle"></i> &nbsp;  {{ p.nombre }}</p>
                                <p class="item_qty" style="font-weight:bold;">Cant: {{ p.cantidad }}</p>
                            </div>
                        </div>
                        <div class="single_item_right_side fix">
                            <p class="single_item_cooking_status" style="">
                                <span ng-if="!p.cooking && !p.done"><i class="fa fa-clock-o"></i></span>
                                <span ng-if="p.cooking"><i class="fa fa-fire"></i> Cocinando</span>
                                <span ng-if="p.done"><i class="fa fa-check"></i> Terminado</span>
                            </p>
                            <p ng-if="p.cooking"><i class="fa fa-clock-o"></i> <span class="text-sm" am-time-ago="p.start_cooking"></span></p>
                            <p ng-if="p.cooking && p.selected"><button class="btn btn-success" ng-click="terminar(p); $event.stopPropagation();">Terminar</button> </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="single_order_button_holder">
                <button class="btn" ng-click="seleccionarTodo(c)">Todo/Nada</button>
                <button class="btn" ng-click="cocinar(c)" >Cocinar</button>
                <button class="btn" ng-click="cancelar(c)" ng-disabled="!c.hay_pendientes">Cancelar</button>
                <button class="btn" ng-click="servir(c)" ng-disabled="!c.hay_cocinando">Servir</button>
                <button class="btn" ng-clikc="cerrarOrden(c)" ng-if="c.completa">Terminar</button>
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

<script>
    $(document).ready(function(){
        $('body').addClass('sidebar-collapse');
        // $(document).on('click', '.single_item', function(){
        //     if ($(this).data('selected') == 'unselected'){
        //         $(this).addClass('selected')
        //         $(this).data('selected', 'selected')
        //         $(this).attr('data-selected', 'selected')
        //     } else {
        //         $(this).removeClass('selected')
        //         $(this).data('selected', 'unselected')
        //         $(this).attr('data-selected', 'unselected')
        //     }
        //     enableButtons($(this))
        // })
    })

    function enableButtons(element){
        var parent = element.closest('.items_holder')
        var items = parent.find('.single_item')
        var selected = []
        $.each(items, function(i,el){
            if ($(el).hasClass('selected')){
                selected.push(el)
            }
        })
        var order = parent.closest('.single_order')

        if (selected.length > 0){
            order.find('.single_order_button_holder').find('.btn').removeAttr('disabled')
        } else {
            order.find('.single_order_button_holder').find('.btn').attr('disabled', 'disabled')
        }
    }
</script>
<style>
    .fix {
        overflow: hidden;
    }
    #order_holder.order_holder .single_order {
        width: 24%;
        margin: 5px .5%;
        border-radius: 5px;
        background: #fff;
        position: relative;
        height: 385px;
    }
    .floatleft {
        float: left;
    }
    #order_holder.order_holder .single_order .header_portion {
        color: #fff;
    }
    #order_holder.order_holder .single_order .header_portion p.order_number {
        margin: 8px 10px;
        line-height: 14px;
    }
    .header_portion.light-blue-background{
        background-color: #0c5889;
    }
    .single_item.selected .item_name i {
        font-size: 20px;
    }
    .single_item.selected {
        background-color: #0392bd;
        color: white
    }
    .single_item.cooking {
        background-color: #fd9237;
        color: white;
    }
    .single_item.selected.cooking {
        background-color: #ff4700;
    }
    .single_item.done {
        background-color: #5DB745;
        color: white;
    }
    .single_item.deliver {
        background-color: #b00;
        color: white;
    }
    .single_order_button_holder {
        background: #cfcfcf;
        position: absolute;
        width: 100%;
        bottom: 0px;
        left: 0px;
    }
    .single_item_left_side {
        width: 60%;
        float: left;
    }
    .single_item_right_side {
        width: 36%;
        float: left;
        padding: 0px 4% 0px 0px;
    }
    .single_item_cooking_status {
        text-align: right;
    }
    .dark-blue-background {
        background-color: #3F679A;
    }
    #order_holder.order_holder .single_order .header_portion p.order_duration {
        display: inline-block;
        padding: 0px 9px;
        line-height: 26px;
        margin: 5px 7px;
        float: right;
        border-radius: 6px;
        font-size: 12px;
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
    .start_cooking_button,.done_cooking{
        display:none;
    }
    #order_holder.order_holder .single_order .items_holder .single_item .item_quantity {
        width: 0%;
        text-align: center;
    }
    #order_holder.order_holder .single_order .items_holder .single_item .item_detail {
        width: 100%;
        padding: 0px 10px;
    }
    #order_holder.order_holder .single_order .items_holder .single_item {
        padding: 8px 0px;
        cursor: pointer;
    }
</style>