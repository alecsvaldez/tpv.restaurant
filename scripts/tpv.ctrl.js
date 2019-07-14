angular.module('app', []).controller('tpv', function ($scope, $http) {
    $scope.comandas = []
    $scope.categorias = []
    $scope.productos = []

    $scope.comanda = {
        nombre: 'comanda',
        productos: [],
        id_usuario: IdUsuario, 
        activa: true
    }
    $scope.categoria = {}

    $scope.enable_product_discount = false
    $scope.enable_total_discount = false
    $scope.nuevaComanda = (apply = false) => {
        var activa = $scope.comandas.find(c => c.activa)
        if (activa) activa.activa = false

        $scope.comanda = {
            nombre: 'comanda',
            productos: [],
            id_usuario: IdUsuario,
            activa: true
        }  
        if (apply)
            $scope.$apply()
    }
    $scope.getComandas = () => {
        $http.get('/ajax/tpv/comandas')
            .then(function (response) {
                $scope.comandas = response.data;
            });
    }
    $scope.getCategorias = () => {
        $http.get('/ajax/tpv/categorias')
            .then(function (response) {
                $scope.categorias = response.data;
                var productos = []
                $scope.categorias.forEach(c => {
                    c.productos.forEach(p => {
                        productos.push(p)    
                    })
                })
                $scope.categorias.unshift({
                    categoria: 'Todo',
                    productos: productos,
                    active: true
                })
                $scope.categoria = $scope.categorias[0]
            });
    }
    // init
    $scope.getComandas()
    $scope.getCategorias()
    
    $scope.setCategoria = (c) => {
        $scope.categoria = c
    }
    $scope.setComanda = (c) => {
        if (c == undefined) {
            $scope.nuevaComanda()
            return
        }
        var activa = $scope.comandas.find(c => c.activa)
        if (activa) activa.activa = false
        $scope.comanda = c
        $scope.comanda.activa = true

        //vamos por info adicional de la comanda
        fetch('/ajax/tpv/comanda/' + $scope.comanda.id_registro)
            .then(response => {
                if (!response.ok) {
                    throw new Error(response.statusText)
                }
                return response.json()
            })
            .then((data) => {
                $scope.comanda = data
                $scope.calculateTotal()
                $scope.$apply()
            })
            .catch(error => {
                console.error(error)
            })
        
    }
    $scope.openProduct = (p) => {
        if (p.en_preparacion == 10) return; // do nothing
        $scope.producto = p
        $('#modal-producto').modal('show')

    }
    $scope.removeQty = (p) => {
        p.cantidad--
        $scope.calculateRow(p)
    }
    $scope.addQty = (p) => {
        p.cantidad++
        $scope.calculateRow(p)
    }
    $scope.calculateRow = (producto, total = true) => {
        var total_row = producto.precio * producto.cantidad
        producto.total = total_row.toFixed(2)
        producto.precio = parseFloat(producto.precio).toFixed(2)
        if (total)
            $scope.calculateTotal()
    }
    $scope.addProducto = (producto) => {
        // primero buscamos el producto en la comanda.
        var prod_in_list = $scope.comanda.productos.findIndex(p => p.id_producto == producto.id_producto && p.en_preparacion != 1)
        if (prod_in_list < 0) {
            prod_in_list = $scope.comanda.productos.length
            producto.cantidad = 1
            $scope.comanda.productos.push(producto)
        } else {
            // Si ya existe, aumentamos la cantidad de ese producto
            $scope.comanda.productos[prod_in_list].cantidad++
        }
        $scope.calculateRow($scope.comanda.productos[prod_in_list])
        
    }
    $scope.calculateTotal = () => {
        console.log('Calculando...')
        var total = 0,
            subtotal = 0,
            subtotal_real = 0
        descuento = 0,
            count_items = 0,
            iva = 0,
            servicio = 0,
            descuento_str = ''
        $scope.comanda.productos.forEach(p => {
            subtotal += parseFloat(p.total)
            count_items += parseFloat(p.cantidad)
        })
        subtotal_real = subtotal

        $scope.comanda.count_items = count_items

        // Ahora aplicamos el descuento
        descuento_str = $scope.comanda.descuento_str || ''
        if ($.trim( descuento_str) != '') {
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
        $scope.comanda.descuento = descuento

        total = subtotal - descuento
        // if ($('#con_iva').iCheck('update')[0].checked) {
        //     $('#iva').removeAttr('disabled').removeClass('disabled');
        //     // Fórmula agregando IVA
        //     // iva = total * (tasa_iva / 100)
        //     // total += iva

        //     // Desglosando IVA
        //     subtotal = total / (1 + (tasa_iva / 100))
        //     iva = parseFloat(total - subtotal)
        // } else {
        //     iva = 0
        //     total += parseFloat(iva)
        //     $('#iva').attr('readonly', 'readonly').addClass('disabled');
        // }

        $scope.comanda.subtotal = parseFloat(subtotal).toFixed(2)
        $scope.comanda.iva = parseFloat(iva).toFixed(2)

        if ($('#con_servicio').is(':checked')) {
            servicio = total * (tasa_servicio / 100)
            total += parseFloat(servicio)
            $scope.comanda.servicio = parseFloat(servicio).toFixed(2)
        }

        // $scope.comanda.descuento = parseFloat(descuento).toFixed(2)
        //$scope.comanda.descuento_str = descuento_str
        $scope.comanda.subtotal_real = parseFloat(subtotal_real).toFixed(2)
        // $scope.comanda.subtotal = parseFloat(subtotal.toFixed(2))
        // $scope.comanda.con_iva = $('#con_iva').iCheck('update')[0].checked
        $scope.comanda.tasa_iva = parseFloat(tasa_iva.toFixed(2))
        // $scope.comanda.iva = parseFloat(iva.toFixed(2))
        $scope.comanda.tasa_servicio = parseFloat(tasa_servicio.toFixed(2))
        $scope.comanda.total = parseFloat(total).toFixed(2)
    }
    var tasa_iva = 16
    var tasa_servicio = 10
    $(document).ready(function () {
        //setUsuarioTPV()

        //fullscreen
        $('body').on('click', '#btn-fullscreen.enterFullScreen', function () {
            $(document.body).fullscreen();
            $(this).addClass('exitFullScreen').removeClass('enterFullScreen')
            $(this).find('i.fa').addClass('fa-compress').removeClass('fa-arrows-alt')
        })
        $('body').on('click', '#btn-fullscreen.exitFullScreen', function () {
            $.fullscreen.exit();
            $(this).removeClass('exitFullScreen').addClass('enterFullScreen')
            $(this).find('i.fa').removeClass('fa-compress').addClass('fa-arrows-alt')
            return false;
        });

        $('#btn-usuario-tpv').click(function () {
            setUsuarioTPV()
        })

        $('body').addClass('sidebar-collapse');
        
        $('.numeric').keydown(function (e) {
            var keys = e.charCode || e.keyCode || 0;
            // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
            // home, end, period, and numpad decimal
            return (
                keys == 8 ||
                keys == 9 ||
                keys == 13 ||
                keys == 46 ||
                keys == 110 ||
                keys == 86 ||
                keys == 190 ||
                (keys >= 35 && keys <= 40) ||
                (keys >= 48 && keys <= 57) ||
                (keys >= 96 && keys <= 105));
        });
        $(document).on("keypress keyup blur", ".numeric-filter", function (event) {            
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
        $(document).on('keyup', '.discount', function (e) {
            var input = $(this).val();
            var ponto = input.split('.').length;
            var slash = input.split('-').length;
            if (ponto > 2)
                $(this).val(input.substr(0, (input.length) - 1));
            $(this).val(input.replace(/[^0-9.%]/, ''));
            if (slash > 2)
                $(this).val(input.substr(0, (input.length) - 1));
            if (ponto == 2)
                $(this).val(input.substr(0, (input.indexOf('.') + 4)));
            if (input == '.')
                $(this).val("");

        });

        $('#con_iva').on('ifChanged', function () {
            // calcula por el iva
            $scope.calculateTotal()
        })

    })
      
    $scope.checkDiscount = () => {
        // valida que no se pueda agregar descuento si no ha prodyuctos
        $scope.calculateTotal()
    }
    $scope.deleter = (producto, index) => {
        
        Swal.fire({
            title: 'Se quitará &nbsp; <i> ' + producto.nombre + '</i>',
            text: "¿Deseas continuar?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                $scope.comanda.productos.splice(index, 1)
                $scope.$apply()
                Swal.fire(
                    'Borrado!',
                    'El producto ha sido eliminado',
                    'success'
                )
            }
        })
        
    }
    
    $scope.cancelarComanda = (comanda) => {
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
                $.ajax({
                    method: 'POST',
                    url: '/ajax/tpv/cancelar-comanda',
                    data: angular.toJson($scope.comanda),
                    dataType: 'json',
                    async: false,
                    success: function (response) {
                        var index = $scope.comandas.findIndex(c => c.activa)
                        $scope.comandas.splice(index, 1)
                        $scope.nuevaComanda(true)
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
        })
    }

    $scope.generarCuenta = () => {
        $.ajax({
            method: 'POST',
            url: '/ajax/tpv/cerrar-comanda',
            data: angular.toJson($scope.comanda),
            dataType: 'json',
            async: false,
            success: function (response) {
                Swal.fire({
                    // position: 'top-end',
                    type: 'success',
                    title: 'La Comanda ha sido cerrada!',
                    showConfirmButton: false,
                    timer: 1500
                })
                if (parseInt(response.id_registro) > 0) {
                    var existe = $scope.comandas.findIndex(c => c.id_registro == response.id_registro)
                    if (existe >= 0) {
                        $scope.comandas.splice(existe, 1)
                    }
                }

                $scope.nuevaComanda()
            },
            error: function (response) {
                Swal.fire({
                    // position: 'top-end',
                    type: 'error',
                    title: 'Ocurrió un error al registrar la comanda!'
                })
            }
        })
    }
    $scope.colocarOrden = () => {
        $scope.comanda.id_mesa = $('#mesa-comanda').find('option:selected').val()

        if ($scope.comanda.id_mesa < 0) {
            Swal.fire({
                // position: 'top-end',
                type: 'error',
                title: 'Por favor selecciona una mesa'
            })
            return;
        }

        $scope.comanda.mesa = $('#mesa-comanda').find('option:selected').text()

        $.ajax({
            method: 'POST',
            url: '/ajax/tpv/comanda',
            data: angular.toJson($scope.comanda),
            dataType: 'json',
            async: false,
            success: function (response) {
                Swal.fire({
                    // position: 'top-end',
                    type: 'success',
                    title: 'La Comanda ha sido enviada!',
                    showConfirmButton: false,
                    timer: 1500
                })
                if (parseInt(response.id_registro) > 0) {
                    var existe = $scope.comandas.find(c => c.id_registro == response.id_registro)
                    if (!existe) {
                        $scope.comanda.id_registro = parseInt(response.id_registro)
                        $scope.comanda.nombre = 'C-' + response.id_registro
                        $scope.comanda.comanda = 'C-' + response.id_registro
                        $scope.comandas.unshift($scope.comanda)
                    }
                }
                
                $scope.nuevaComanda()   
                // comandas.unshift({
                //     nombre: 'C-0',
                //     comanda: 'C-0',
                //     id_usuario: IdUsuario, 
                //     productos: [],
                //     activa: true
                // })
            },
            error: function (response) {
                Swal.fire({
                    // position: 'top-end',
                    type: 'error',
                    title: 'Ocurrió un error al registrar la comanda!'
                })
            }
        })
    }
    
    function validatePin() {
        var pin = $('.input-pin').val()
        return fetch('/ajax/tpv/pin/' + pin)
            .then(response => {
                if (!response.ok) {
                    throw new Error(response.statusText)
                }
                return response.json()
            })
            .then((json) => {
                if (json.id_usuario == 0) {
                    throw new Error(json.message)
                }
                var kb = $('.input-pin').getkeyboard();
                kb.close()
                IdUsuario = json.id_usuario
                if ($scope.comanda) {
                    $scope.comanda.id_usuario = json.id_usuario
                }
                $('#nombre-atiende').html(json.nombre)
                return json;
            })
            .catch(error => {
                Swal.showValidationMessage(
                    `${error}`
                )
            })
    }
    function pinIsOk(result) {
        if (result) {
            Swal.fire({
                // position: 'top-end',
                type: 'success',
                title: 'Pin Válido!',
                showConfirmButton: false,
                timer: 1500
            })
        }
    }
    function setUsuarioTPV() {
        Swal.fire({
            position: 'top',
            title: 'Introduce tu PIN de usuario',
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
            cancelButtonText: 'Salir',
            cancelButtonColor: '#c9302c',
            confirmButtonText: 'Entrar',
            confirmButtonColor: '#449d44',
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                return validatePin()
            },
        }).then((result) => {
            if (result.value) {
                // NO MOVER el value, porque es parte del promise de sweetalert
                pinIsOk(result.value)
            } else if (
                result.dismiss === Swal.DismissReason.cancel
            ) {
                window.location = '/'
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
            })
                .addTyping();
            $.keyboard.keyaction.enviar = function (base) {
                validatePin().then((result) => {
                    pinIsOk(result)
                })
            };
        }
    }
    function toggleFullScreen() {
        // Mozilla's proposed API: in practice, you'll need vendor prefixes (see examples below)
        if (typeof document.cancelFullScreen != 'undefined' && document.fullScreenEnabled === true) {
            /* do fullscreen stuff */
        }
    }
        
});

