var app = angular.module('app', ['angularMoment']).controller('caja', function ($scope, $http) { 
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
                    c.total = parseFloat(c.total)
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
        if ( c.iva_desglosado == 1 ) {
            // Fórmula agregando IVA
            // iva = total * (tasa_iva / 100)
            // total += iva

            // Desglosando IVA
            subtotal = total / (1 + (tasa_iva / 100))
            iva = parseFloat(total - subtotal)
        } else {
            iva = 0
            total += parseFloat(iva)
        }

        c.subtotal = parseFloat(subtotal).toFixed(2)
        c.iva = parseFloat(iva).toFixed(2)

        if ( c.con_servicio ) {
            servicio = total * (tasa_servicio / 100)
            total += parseFloat(servicio)
            c.servicio = parseFloat(servicio).toFixed(2)
        }

        // c.descuento = parseFloat(descuento).toFixed(2)
        //c.descuento_str = descuento_str
        c.subtotal_real = parseFloat(subtotal_real).toFixed(2)
        // c.subtotal = parseFloat(subtotal.toFixed(2))
        // c.con_iva = $('#con_iva_' + c.id_registro).iCheck('update')[0].checked

        c.tasa_iva = parseFloat(tasa_iva.toFixed(2))
        // c.iva = parseFloat(iva.toFixed(2))
        c.tasa_servicio = parseFloat(tasa_servicio.toFixed(2))
        c.total = parseFloat(total).toFixed(2)
    }
    

    $scope.openCaja = (c) => {
        $scope.comanda = c
        $('#modal-cobrar').modal('show')
    }
    $('#modal-cobrar').on('shown.bs.modal', function () {
        $('input[name="efectivo"]').focus();
    }) 

    $scope.calculaCambio = () => {
        $scope.comanda.cambio = $scope.comanda.efectivo - ($scope.comanda.total - ($scope.comanda.tarjeta || 0))
    }

    $scope.cobrar = (c) => {
        if (c.tarjeta > 0 && (!c.tarjeta_banco || !c.tarjeta_num)) {
            Swal.fire({
                title: 'Por favor proporciona la información de la tarjeta.',
                text: "Error",
                type: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok',
            })
            return
        }
        if ( parseFloat((c.efectivo || 0) + (c.tarjeta || 0)) < parseFloat(c.total) ) {
            console.log()
            Swal.fire({
                title: 'No se puede cobrar un importe menor al total de la cuenta.',
                html:
                    "<b>Cobrando:</b> $" + (parseFloat(c.tarjeta || 0) + parseFloat(c.efectivo || 0)) + " <br>"+
                    "<b>Total a pagar:</b> $" + $scope.comanda.total,
                type: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok',
            })
            return;
        }
        
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
                        $scope.comanda = c
                        $scope.$apply()
                        $('#modal-cobrar').modal('hide')
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
    $scope.regresarComanda = (c) => {
        Swal.fire({
            title: 'Se regresará la comanda actual al punto de venta',
            text: "¿Deseas continuar?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Sí, regresar',
            cancelButtonColor: '#d33',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.value) {
                $scope.comanda = c
                requestAdminValidation('regresar')
            }
        })
    }
    $scope.cancelarCuenta = (c) => {
        Swal.fire({
            title: 'Se cancelará la comanda actual',
            text: "¿Deseas continuar?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Sí, cancelar',
            cancelButtonColor: '#d33',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.value) {
                $scope.comanda = c
                requestAdminValidation('cancelar')
            }
        })
    }

    function requestAdminValidation( proceso ) {
        Swal.fire({
            position: 'top',
            title: 'Introduce un PIN de administrador',
            input: 'password',
            inputAttributes: {
                autocapitalize: 'off',
                maxlength: 6
            },
            customClass: {
                input: 'input-pin'
            },
            allowOutsideClick: false,
            allowEscapeKey: false,
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            cancelButtonColor: '#c9302c',
            confirmButtonText: 'Autorizar',
            confirmButtonColor: '#449d44',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return validateAdminPin()
            },
        }).then((result) => {
            if (result.value) {
                // NO MOVER el value, porque es parte del promise de sweetalert
                if (result.value) {
                    switch (proceso) {
                        case 'cancelar': sendCancel(result.value); break;
                        case 'regresar': sendReturn(); break;
                    }
                }                
            } else if (
                result.dismiss === Swal.DismissReason.cancel
            ) {
                // do nothing
            }

        })
        if (Swal.isVisible()) {
            $('.input-pin').keyboard({
                // layout : 'num',
                layout: 'custom',
                customLayout: {
                    'default': [
                        "7 8 9 {b}",
                        "4 5 6 {c}",
                        "1 2 3 {enviar!!}",
                        "0 "
                    ]
                },
                display: {
                    'enviar': '✔',
                },
                restrictInput: true, // Prevent keys not in the displayed keyboard from being typed in
                preventPaste: true,  // prevent ctrl-v and right click
                autoAccept: true,
                usePreview: false,
                maxLength: 6, // max input de PIN
                css: {
                    // input & preview
                    input: 'form-control input-sm',
                    // keyboard container
                    container: 'center-block dropdown-menu', // jumbotron
                    // default state
                    buttonDefault: 'btn btn-default btn-lg',
                    // hovered button
                    buttonHover: 'btn-primary',
                    // Action keys (e.g. Accept, Cancel, Tab, etc);
                    // this replaces "actionClass" option
                    buttonAction: 'btn-action',
                    // used when disabling the decimal button {dec}
                    // when a decimal exists in the input area
                    buttonDisabled: 'disabled'
                }
            }).addTyping();
            
            $.keyboard.keyaction.enviar = function (base) {
                validateAdminPin().then((result) => {
                    // do action
                    sendCancel(result)
                })
            };
        }
    }    
    function validateAdminPin() {
        var pin = $('.input-pin').val()
        return fetch('/ajax/caja/admin-pin/' + pin)
            .then(response => {
                // response de XHR
                if (!response.ok) {
                    throw new Error(response.statusText)
                }
                return response.json()
            })
            .then((json) => {
                // response real
                if (!json.id_usuario) {
                    throw new Error(json.message)
                }
                var kb = $('.input-pin').getkeyboard();
                kb.close()
                return json;
            })
            .catch(error => {
                Swal.showValidationMessage(
                    `${error}`
                )
            })        
    }
    function sendReturn() {
        $.ajax({
            method: 'POST',
            url: '/ajax/caja/regresar-comanda',
            data: angular.toJson($scope.comanda),
            dataType: 'json',
            async: false,
            success: function (response) {
                var index = $scope.comandas.findIndex(c => c.id_registro == $scope.comanda.id_registro)
                $scope.comandas.splice(index, 1)
                $scope.comanda = {}
                $scope.$apply()
                Swal.fire({
                    // position: 'top-end',
                    type: 'success',
                    title: 'La Comanda ha sido regresada al punto de venta!',
                    showConfirmButton: false,
                    timer: 1500
                })

            }
        })

    }
    function sendCancel(json) {
        $scope.comanda.id_autoriza_cancela = json.id_usuario
        $.ajax({
            method: 'POST',
            url: '/ajax/caja/cancelar-comanda',
            data: angular.toJson($scope.comanda),
            dataType: 'json',
            async: false,
            success: function (response) {
                var index = $scope.comandas.findIndex(c => c.id_registro == $scope.comanda.id_registro)
                $scope.comandas.splice(index, 1)
                $scope.comanda = {}
                $scope.$apply()
                Swal.fire({
                    // position: 'top-end',
                    type: 'success',
                    title: 'La Comanda ha sido cancelada!',
                    showConfirmButton: false,
                    timer: 1500
                })

            }
        })
        
    }
    $(document).ready(function () { 

    })
})
