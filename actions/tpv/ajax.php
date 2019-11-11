<?php
namespace TPV;
use TPV\Models\User;

defined('_PUBLIC_ACCESS') or die();
// http_response_code(200);
// header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $data = file_get_contents('php://input');
    $data = json_decode($data, true);
}

switch($accion){
    case 'pin':
        $user = (new User)->getUserByPin(array('pin' => $_GET['id']));
        if ( $user !== false &&  $user['result'] == 'ok' ){
            $_SESSION['tpv'] = array(
                'id_usuario' => $user['id'],
                'usuario' => $user['username'],
                'nombre' => $user['nombre']
            );
            $response = $_SESSION['tpv'];
        } else {
            $_SESSION['tpv'] = array();
            $response = array ('id_usuario' => 0, 'message' => 'Pin Incorrecto.');
        }
        echo json_encode($response);
        exit;
    break;
    case 'comanda': 
        $table = 'tb_comandas';
        if ($_SERVER['REQUEST_METHOD'] == 'GET'){
            $id = $_GET['id'];
            $comanda = $db->first("SELECT
                c.id AS id_registro
                , CONCAT('C-', c.id) AS nombre
                , CONCAT('C-', c.id) AS comanda
                , c.Comanda AS nombre
                , c.IdMesa AS id_mesa
                , c.IdCliente AS id_cliente
                , c.IdAtiende AS id_usuario
                , CONCAT(
                    SUBSTRING_INDEX(u.Nombre, ' ', 1), 
                    ' ', 
                    SUBSTRING_INDEX(u.Apellidos, ' ', 1)
                ) AS atiende
                , c.RecibidoCocina AS en_cocina
                , c.TerminadoCocina AS terminado
                , c.OrdenCerrada AS orden_cerrada
                , c.IdTicket AS id_ticket
                , c.ConServicio AS con_servicio
                , c.TasaServicio AS tasa_servicio
                , c.ConIva AS con_iva
                , c.TasaIva AS tasa_iva
                , c.Subtotal AS subtotal
                , c.SubtotalReal AS subtotal_real
                , c.DescuentoStr AS descuento_str
                , c.Descuento AS descuento
                , c.IVA AS iva
                , c.Total AS total
                , c.Pagado AS pagado
                , c.OrdenPagada AS orden_pagada
            FROM $table c
                LEFT  JOIN tb_usuarios u ON u.id = c.IdAtiende
            WHERE c.id = $id ");
            
            $productos_comanda = $db->get("SELECT
                    cp.id AS id_registro
                    , cp.IdProducto AS id_producto
                    , p.Producto AS nombre
                    , p.IdCategoria AS id_categoria
                    , c.Categoria AS categoria
                    , cp.Precio AS precio
                    , CASE (cp.Cantidad MOD 1 > 0) WHEN true THEN ROUND(cp.Cantidad, 2) ELSE ROUND(cp.Cantidad, 0) END AS cantidad
                    , cp.DescuentoStr AS descuento_str
                    , cp.Descuento AS descuento
                    , cp.Total AS total
                    , cp.Comentarios AS comentarios
                    , cp.EnPreparacion AS en_preparacion
                FROM tb_comandas_productos cp
                    INNER JOIN tb_productos p ON p.id = cp.IdProducto
                    INNER JOIN tb_cat_productos c ON c.id = p.IdCategoria
                WHERE cp.Estatus = 1 
                    AND cp.IdComanda = $id");
            $comanda['productos'] = $productos_comanda;

            $response = $comanda;
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $comanda = array(
                'Comanda' => isset($data['nombre']) ? $data['nombre'] : 'C-0',
                'IdMesa' => isset($data['id_mesa']) ? $data['id_mesa'] : 0,
                'SubtotalReal' => $data['subtotal_real'],
                'Subtotal' => $data['subtotal'],
                'DescuentoStr' => isset($data['descuento_str']) && $data['descuento_str'] != '' ? $data['descuento_str']  : '',
                'Descuento' => $data['descuento'],
                //'ConIVA' => $data['con_iva'] == true ? 1 : 0,
                //'TasaIva' => $data['tasa_iva'] == true ? 1 : 0,
                //'IVA' => $data['iva'],
                // Agregar columnas del servicio
                'Total' => $data['total'],
                'IdAtiende' => $data['id_usuario'],
                'IdCliente' => isset($data['id_cliente']) ? $data['id_cliente'] : 0,
            );
            // $db->enableShowSql();
            if (isset($data['id_registro']) && $data['id_registro'] > 0){
                $comanda['IdUsuarioModifica'] = $_SESSION['id'];
                $comanda['FechaModifica'] = date('Y-m-d H:i:s');
                $id_comanda = $db->updateById($table, $comanda, 'id', $data['id_registro']);
            } else {    
                $comanda['IdUsuarioCrea'] = $_SESSION['id'];
                $comanda['FechaCrea'] = date('Y-m-d H:i:s');
                $id_comanda = $db->insert($table,$comanda);
            }
            if ($id_comanda !== false && !is_null($id_comanda)){
                // Antes de enviar la respuesta, es necesario registrar los productos
                
                $productos = $data['productos'];
                foreach($productos as $p){
                    $producto = array(
                        'IdComanda' => $id_comanda,
                        'IdProducto' => $p['id_producto'],
                        'Precio' => isset($p['precio']) ? $p['precio'] : 0,
                        'Cantidad' => isset($p['cantidad']) ? $p['cantidad'] : 0,
                        // Aquí va el descuento, pero todavía no lo tenemos listo
                        'Total' => isset($p['total']) ? $p['total'] : 0,
                        'Comentarios' => isset($p['comentarios']) ? $p['comentarios'] : '',
                        'EnPreparacion' => 1
                    );
                    if (isset($p['id_registro']) && $p['id_registro'] > 0){
                        // Update
                        $producto['IdUsuarioModifica'] = $_SESSION['id'];
                        $producto['FechaModifica'] = date('Y-m-d H:i:s');
                        $db->updateById('tb_comandas_productos', $producto, 'id', $p['id_registro']);
                    } else {
                        // Insert
                        $producto['IdUsuarioCrea'] = $_SESSION['id'];
                        $producto['FechaCrea'] = date('Y-m-d H:i:s');
                        $db->insert('tb_comandas_productos', $producto);
                    }
                }
                $response = array ('id_registro' => $id_comanda, 'message' => 'Los datos se han guardado.');
                // Ahora se imprime el ticket de la comanda en cocina
                // update a la comanda para marcarla como cobrada/cerrada
                $c = $data;
                $c['nombre'] =  'C-' . $c['id_mesa'] . '-' . $id_comanda;
                require ROOTPATH . 'lib/printer_cocina.php'; 
            } else {
                $error = $db->executeError();
                $response = array ('id_registro' => 0, 'message' => 'Error al guardar: ' . $error['db_message']);
            }
        }
        echo json_encode($response);
        exit;
    break;
    case 'comandas':
        // Traemos las comandas existentes
        $comandas_db = $db->get("SELECT 
            id AS id_registro
            , CONCAT(Comanda, '-', id) AS nombre
            , CONCAT('C-', id) AS comanda
            , IdAtiende AS id_usuario
            , DescuentoStr AS descuento_str
            , Descuento AS descuento
            , SubtotalReal AS subtotal_real
            , Subtotal AS subtotal
            , ConIva AS con_iva
            , TasaIva AS tasa_iva
            , IVA AS iva
            , Total AS total
            , 0 AS activa
        FROM tb_comandas
        WHERE Estatus = 1
            AND OrdenCerrada = 0
            AND IdTicket = 0
        ORDER BY id DESC");

        $comandas = array(array(
                'id_registro' => 0,
                'nombre' => 'Comanda',
                'comanda' => 'Nueva',
                'id_usuario' => $_SESSION['id'],
                'productos' => [],
                'activa' => true
            ));

        $comandas = array();
        foreach($comandas_db as &$c){
            // Por cada comanda vamos por los productos
            $productos_comanda = $db->get("SELECT
                cp.id AS id_registro
                , cp.IdProducto AS id_producto
                , p.Producto AS nombre
                -- , c.Categoria AS categoria
                , cp.Precio AS precio
                , cp.Cantidad AS cantidad
                , cp.DescuentoStr AS descuento_str
                , cp.Descuento AS descuento
                , cp.Total AS total
            FROM tb_comandas_productos cp
                INNER JOIN tb_productos p ON p.id = cp.IdProducto
                -- INNER JOIN tb_cat_productos c ON c.id = p.IdCategoria
            WHERE cp.Estatus = 1 
                AND cp.IdComanda = " . $c['id_registro']);
            $c['productos'] = $productos_comanda;
            $comandas[] = $c;
        }
        echo json_encode($comandas);
        exit;
    break;
    case 'categorias': 
        $id_menu = 3;
        $productos = $db->get("SELECT
                    m.id AS id
                    , p.id AS id_producto
                    , Producto AS nombre
                    , p.IdCategoria AS id_categoria
                    , Categoria AS categoria
                    , m.Precio AS precio_menu
                    , p.PrecioBase AS precio
                    , p.CostoBase AS costo
                FROM tb_productos p
                    INNER JOIN tb_cat_productos c ON c.id = p.IdCategoria
                    INNER JOIN tb_menus_productos m ON m.IdProducto = p.id
                WHERE p.Estatus = 1
                    AND m.Estatus = 1
                    AND m.IdMenu = $id_menu");

        $categorias = array();
        foreach($productos as $p){
            $categoria = clean($p['categoria']);
            if (!isset($categorias[$categoria])){
                $categorias[$categoria] = array(
                    'id' => $categoria,
                    'id_categoria' => $p['id_categoria'],
                    'categoria' => $p['categoria'],
                    'productos' => array()
                );
            }
            $categorias[$categoria]['productos'][] = $p;
        }
        echo json_encode(array_values($categorias));
        exit;
    break;
    case 'cancelar_comanda':
        $id_registro = isset($data['id_registro'] ) ? $data['id_registro']  : 0;
        $comanda = array(
            'Estatus' => 0,
            'IdUsuarioModifica' => $_SESSION['id'],
            'FechaModifica' => date('Y-m-d H:i:s')
        );
        $id_comanda = $db->updateById('tb_comandas', $comanda, 'id', $id_registro);
        if ($id_comanda !== false){
            $response = array ('id_registro' => $id_comanda, 'message' => 'La comanda ha sido cancelada');
        } else {
            $error = $db->executeError();
            $response = array ('id_registro' => 0, 'message' => 'Error al cancelar: ' . $error['db_message']);
        }
        echo json_encode($response);
        exit;
    break;
    case 'cerrar_comanda':
        $id_registro = isset($data['id_registro'] ) ? $data['id_registro']  : 0;
        $comanda = array(
            'OrdenCerrada' => 1,
            'SubtotalReal' => $data['subtotal_real'],
            'Subtotal' => $data['subtotal'],
            'Total' => $data['total'],
            'IdUsuarioCierra' => $_SESSION['id'],
            'FechaCierra' => date('Y-m-d H:i:s')
        );
        $id_comanda = $db->updateById('tb_comandas', $comanda, 'id', $id_registro);
        if ($id_comanda !== false){
            $response = array ('id_registro' => $id_comanda, 'message' => 'La comanda ha sido cerrada');
            // generamos la cuenta
            $c = $data;
            require ROOTPATH . 'lib/printer.php'; 
        } else {
            $error = $db->executeError();
            $response = array ('id_registro' => 0, 'message' => 'Error al cerrar: ' . $error['db_message']);
        }
        echo json_encode($response);
        exit;
    break;
    default: echo 'No valido';print_array($data); exit;
}
