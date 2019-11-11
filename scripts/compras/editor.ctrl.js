var app = angular.module('app', []).controller('compras_editor', function ($scope, $http) {
    
    $scope.getItem = () => {
        $http.get( AJAX + 'compras/detalle/' + id)
            .then(function (response) {
                $scope.item = response.data;
                $scope.item.serial_id = ('00000' + $scope.item.id).slice(-5)
                $scope.item.id_proveedor = '' + $scope.item.id_proveedor

                console.log($scope.item)
                let select = $('select[name="id_proveedor"]')
                select.val($scope.item.id_proveedor);
                setTimeout(() => {                    select.trigger('change');
                }, 1)
            });
    }
    $scope.getItem()

    $scope.ingredientes = []
    $('#select-productos option').each((i, v) => { 
        if ($(v).val() > 0) {
            $scope.ingredientes.push({
                id: $(v).val(),
                ingrediente: $(v).text() 
            })    
        }
    })

})
/*
$('.btn-add').on('click', function () {
    var select = $(this).closest('.input-group').find('select')
    if (select.val() > 0) {
        // Si hay una opcion seleccionada, vamos por el registro
        var url = '/ajax/' + select.attr('data-url') + select.val()
        select.val(null).trigger('change')
        $.ajax({
            method: 'GET',
            url: url,
            success: function (response) {
                var list = $('#lista-compras'), index = Math.random() * -1
                if (response) {
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
$('#check-orden-cerrada').on('ifChanged', function () {
    if ($(this).is(':checked')) {
        orderClose()
    } else {
        $('.select-item').removeAttr('disabled').select2({ 'disabled': false });
        $('.btn-add').removeAttr('disabled')
        $('.precio, .cantidad, .total, .pagado').removeAttr('readonly')
    }
})

$(document).on('blur', '.precio, .cantidad', function () {
    var fila = $(this).closest('.row-ingrediente');
    sumaFila(fila)
    sumaTotal()
})
$(document).on('blur', '.pagado', function () {
    saldos()
})

function sumaFila(fila) {
    var precio = fila.find('.precio').val() || 0,
        cantidad = fila.find('.cantidad').val() || 0,
        total = 0
    total = precio * cantidad
    fila.find('.total').val(total)
}
function sumaTotal() {
    var total = 0;
    $('.total').each(function (k, i) {
        total += parseFloat($(i).val())
    })
    $('.saldo-total').val(total)
}
function saldos() {
    var total = $('.saldo-total').val(),
        pagado = $('.pagado').val() || 0
    if (total == 0 && pagado > 0) {
        alert('No se puede tener saldo pagado si el total a pagar es $ 0');
        return
    }
    $('.pendiente').val(total - pagado)
}
function orderClose() {
    $('.btn-add').attr('disabled', 'disabled')
    $('.select-item').attr('disabled', 'disabled').select2({ 'disabled': true }).val(null).trigger('change');
    $('.precio, .cantidad, .total, .pagado').attr('readonly', 'readonly')
}
$(document).ready(function () {
    // <? php
    // if ($item['orden_cerrada'] == 1) echo ' orderClose(); ';
    // ?>
})
*/