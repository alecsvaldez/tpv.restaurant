angular.module('app', ['angularMoment']).controller('caja', function ($scope, $http) { 
    var tasa_iva = 16
    var tasa_servicio = 10

    $scope.getComandas = () => {
        $http.get('/ajax/caja/comandas')
            .then(function (response) {
                $scope.comandas = response.data;
                $scope.comandas.forEach(c => {
                    c.count_items = 0
                    c.generada = new Date(c.generada * 1000)
                    c.productos.forEach(p => {
                        c.count_items += parseFloat(p.cantidad)
                    })
                })
            });        
    }


    $scope.selected = []
    // init
    $scope.getComandas()

    $scope.checkDiscount = (c) => {
        // valida que no se pueda agregar descuento si no ha prodyuctos
        $scope.calculateTotal(c)
    }

    $scope.calculateTotal = (c) => {
        console.log('Calculando...')
        let total = 0,
            subtotal = 0 ,
            subtotal_real = 0
        descuento = 0,
            count_items = 0,
            iva = 0,
            servicio = 0,
            descuento_str = ''
        c.productos.forEach(p => {
            subtotal += parseFloat(p.total)
            count_items += parseFloat(p.cantidad)
        })
        subtotal_real = subtotal

        c.count_items = count_items
        // Ahora aplicamos el descuento
        descuento_str = c.descuento_str || ''
        if ($.trim(descuento_str) != '') {
            var disc_values = descuento_str.split('%');
            var descuento_val = parseFloat(disc_values[0]);
            var descuento_type = disc_values[1];

            if (descuento_str.includes('%')) {
                descuento = (subtotal * (parseFloat($.trim(descuento_val)) / 100));
            } else {
                descuento = descuento_val
            }
        }
        descuento = parseFloat(descuento).toFixed(2)
        c.descuento = descuento

        total = subtotal - descuento
        // if ($('#con_iva_' + c.id_registro ).iCheck('update')[0].checked) {
        if ($('#con_iva_' + c.id_registro ).is(':checked') ) {
            $('#iva_' + c.id_registro).removeAttr('disabled').removeClass('disabled');
            // Fórmula agregando IVA
            // iva = total * (tasa_iva / 100)
            // total += iva

            // Desglosando IVA
            subtotal = total / (1 + (tasa_iva / 100))
            iva = parseFloat(total - subtotal)
        } else {
            iva = 0
            total += parseFloat(iva)
            $('#iva_' + c.id_registro).attr('readonly', 'readonly').addClass('disabled');
        }

        c.subtotal = parseFloat(subtotal).toFixed(2)
        c.iva = parseFloat(iva).toFixed(2)

        if ($('#con_servicio_' + c.id_registro).is(':checked')) {
            servicio = total * (tasa_servicio / 100)
            total += parseFloat(servicio)
            c.servicio = parseFloat(servicio).toFixed(2)
        }

        // c.descuento = parseFloat(descuento).toFixed(2)
        //c.descuento_str = descuento_str
        c.subtotal_real = parseFloat(subtotal_real).toFixed(2)
        // c.subtotal = parseFloat(subtotal.toFixed(2))
        // c.con_iva = $('#con_iva_' + c.id_registro).iCheck('update')[0].checked
        c.con_iva = $('#con_iva_' + c.id_registro).is(':checked')
        c.tasa_iva = parseFloat(tasa_iva.toFixed(2))
        // c.iva = parseFloat(iva.toFixed(2))
        c.tasa_servicio = parseFloat(tasa_servicio.toFixed(2))
        c.total = parseFloat(total).toFixed(2)
    }
    

    $scope.openCaja = (c) => {
        console.log(c)
        $scope.comanda = c
        $('#modal-cobrar').modal('show')
    }

    $scope.cobrar = (c) => {
        Swal.fire({
            title: 'Se procederá al cobro de la comanda comanda actual',
            text: "¿Deseas continuar?",
            type: 'question',
            showCancelButton: true,
            cancelButtonColor: '#d33',
            cancelButtonText: 'No',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Sí, continuar',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    method: 'POST',
                    url: '/ajax/caja/cobrar-comanda',
                    data: angular.toJson(c),
                    dataType: 'json',
                    async: false,
                    success: function (response) {
                        var index = $scope.comandas.findIndex(cc => cc.id_registro == c.id_registro)
                        $scope.comandas.splice(index, 1)
                        $scope.$apply()
                        Swal.fire({
                            // position: 'top-end',
                            type: 'success',
                            title: 'La Comanda ha sido cobrada!',
                            showConfirmButton: false,
                            timer: 1500
                        })

                    }
                })
            }
        })

    }
  
    $(document).ready(function () { 

    })
})
