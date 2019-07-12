angular.module('app', ['angularMoment']).controller('cocina', function ($scope, $http) { 
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

    $scope.toggleSelected = (p) => {
        p.selected = p.selected ? false : true
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
            // todo seleccionado, quitamos selected
            c.productos.map(p => {
                p.selected = false
            })
            // los quitamos de selected
            c.productos.forEach(p => {
                var existe = $scope.selected.findIndex(s => s.id_registro == p.id_registro)
                if (existe >= 0) {
                    $scope.selected.splice(existe, 1);
                }
            })
        } else {
            c.productos.map(p => {
                p.selected = true
                // los agregamos a selected
                var existe = $scope.selected.find(s => s.id_registro == p.id_registro)
                if (!existe) {
                    $scope.selected.push(p)
                }
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
            p.cooking = true
            p.selected = false
            var existe = $scope.selected.findIndex(s => s.id_registro == p.id_registro)
            if (existe >= 0) {
                $scope.selected.splice(existe, 1);
            }
            return true
        })
        c.hay_cocinando = true
    }

    $scope.servir = (c) => {
        
    }
})
