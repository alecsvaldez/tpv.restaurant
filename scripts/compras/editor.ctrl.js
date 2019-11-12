var app = angular.module('app', []).controller('compras_editor', function ($scope, $http) {
    
    $scope.getItem = () => {
        if (id > 0) {
            $http.get( AJAX + 'compras/detalle/' + id)
                .then(function (response) {
                    $scope.item = response.data;
                    $scope.item.serial_id = ('00000' + $scope.item.id).slice(-5)
                    $scope.item.id_proveedor = '' + $scope.item.id_proveedor
    
                    
                    let select = $('select[name="id_proveedor"]')
                    select.val($scope.item.id_proveedor);
                    setTimeout(() => {
                        select.trigger('change');
                    }, 1)
                });
        } else {
            $scope.item = {
                serial_id: 0,
                id_proveedor: 0,
                items: [],
                saldo_total: 0,
                saldo_pagado: 0,
                saldo_pendiente: 0
            }
        }
    }
    $scope.getItem()

    $scope.ingredientes = []
    // Convertimos ingredientes de select (php) a array (JS)
    $('#select-productos option').each((i, v) => { 
        if ($(v).val() > 0) {
            $scope.ingredientes.push({
                id: $(v).val(),
                ingrediente: $(v).text() 
            })
        }
    })

    $scope.addProduct = () => {
        var select = $('#select-productos')
        if (select.val() > 0) {
            // Si hay una opcion seleccionada, vamos por el registro
            var url = AJAX + select.attr('data-url') + select.val()
            select.val(null).trigger('change')
            $.ajax({
                method: 'GET',
                url: url,
                success: function (response) {
                    $scope.item.items.push({
                        id_registro: 0,
                        id_item: response.id_item,
                        item: response.item,
                        id_categoria: response.id_categoria,
                        categoria: response.categoria,
                        id_unidad_entrada: response.id_unidad_entrada,
                        unidad_entrada: response.unidad_entrada,
                        id_unidad_salida: response.id_unidad_salida,
                        unidad_salida: response.unidad_salida,
                        conversion: parseFloat(response.conversion),
                        precio: 0,
                        cantidad: 1
                    })
                    $scope.$apply()
                }
            })
        }
    }
    
    $scope.calculate = (p) => {
        if (p != undefined) {
            p.total = (p.precio || 0) * (p.cantidad || 1)
        }
        let total = $scope.item.items.reduce((a, b) => a == undefined ? b.total : b == undefined ? a : a + b.total, 0)
        $scope.item.saldo_total = total
        $scope.item.saldo_pendiente = total - $scope.item.saldo_pagado
    }
})
/*

$('#check-orden-cerrada').on('ifChanged', function () {
    if ($(this).is(':checked')) {
        orderClose()
    } else {
        $('.select-item').removeAttr('disabled').select2({ 'disabled': false });
        $('.btn-add').removeAttr('disabled')
        $('.precio, .cantidad, .total, .pagado').removeAttr('readonly')
    }
})

function orderClose() {
    $('.btn-add').attr('disabled', 'disabled')
    $('.select-item').attr('disabled', 'disabled').select2({ 'disabled': true }).val(null).trigger('change');
    $('.precio, .cantidad, .total, .pagado').attr('readonly', 'readonly')
}