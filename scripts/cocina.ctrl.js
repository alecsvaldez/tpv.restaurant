var app = angular.module('app', []).controller('cocina', function ($scope, $http) { 
    $scope.getComandas = () => {
        $http.get('/ajax/cocina/comandas')
            .then(function (response) {
                $scope.comandas = response.data;
                $scope.comandas.forEach(c => {
                    c.generada = new Date(c.generada * 1000)
                    c.hay_pendientes = true
                    c.hay_cocinando = false
                })
            });        
    }
    $scope.selected = []
    // init
    $scope.getComandas()

    $scope.openProduct = (p) => {
        $scope.producto = p
        $('#modal-producto').modal('show')
    }

    $scope.toggleSelected = (p, status) => {
        if (p.deliver) return 
        if (status == undefined) {
            p.selected = p.selected ? false : true
        } else {
            p.selected = status
        }
        if (p.selected) {
            //buscamos el producto en selected, si no está lo agregamos
            var existe = $scope.selected.find(s => s.id_registro == p.id_registro)
            if (!existe) {
                $scope.selected.push(p)
            }
        } else {
            var existe = $scope.selected.findIndex(s => s.id_registro == p.id_registro)
            if (existe >= 0) {
                $scope.selected.splice(existe, 1);
            }
        }
    }

    $scope.seleccionarTodo = (c) => {        
        var selected = c.productos.filter(p => p.selected)
        if (selected.length == c.productos.length) {
            c.productos.forEach(p => {
                $scope.toggleSelected(p, false)
            })
        } else {
            c.productos.forEach(p => {
                $scope.toggleSelected(p, true)
            })
        }
    }

    let getSelected = (productos) => {
        return productos.filter(p => p.selected)
    }

    $scope.cocinar = (c) => {
        var productos = getSelected(c.productos)
        if (productos.length == 0) {
            alert('No hay productos seleccionados para cocinar')
            return
        } 
        productos.map(p => {
            if (p.cooking || p.done || p.deliver) return 
            p.cooking = true
            p.start_cooking = new Date()
            p.selected = false
            $scope.toggleSelected(p, false)
            return true
        })
        c.hay_cocinando = true        
    }

    $scope.servir = (c) => {
        c.productos.forEach(p => {
            if (p.done) {
                p.deliver = true
            }
            $scope.toggleSelected(p, false)
        })
        
    }

    $scope.terminar = (p) => {
        p.cooking = false
        p.done = true
        $scope.toggleSelected(p, false)
    }
})
