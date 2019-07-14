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
    case 'comandas':
        // Traemos las comandas existentes
        $comandas_db = $db->get("SELECT 
            c.id AS id_registro
            , CONCAT('C-', c.IdMesa, '-', c.id) AS nombre
            , CONCAT('C-', c.IdMesa, '-', c.id) AS comanda
            , m.id AS id_mesa
            , CONCAT( IFNULL(m.Mesa, 'Mesa') , ' ',  m.id) AS mesa 
            , IdAtiende AS id_usuario
            , c.FechaCrea AS generada
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
        FROM tb_comandas c
            LEFT JOIN tb_mesas m ON m.id = c.IdMesa
        WHERE c.Estatus = 1
            AND OrdenCerrada = 1
            -- AND OrdenPagada = 0
            -- AND IdTicket = 0
        ORDER BY c.id");

        $comandas = array();
        foreach($comandas_db as &$c){
            // Por cada comanda vamos por los productos
            $productos_comanda = $db->get("SELECT
                cp.id AS id_registro
                , cp.IdProducto AS id_producto
                , p.Producto AS nombre
                , c.Categoria AS categoria
                , cp.Precio AS precio
                , CASE (cp.Cantidad MOD 1 > 0) WHEN true THEN ROUND(cp.Cantidad, 2) ELSE ROUND(cp.Cantidad, 0) END AS cantidad
                , cp.DescuentoStr AS descuento_str
                , cp.Descuento AS descuento
                , cp.Total AS total
            FROM tb_comandas_productos cp
                INNER JOIN tb_productos p ON p.id = cp.IdProducto
                INNER JOIN tb_cat_productos c ON c.id = p.IdCategoria
            WHERE cp.Estatus = 1 
                AND cp.IdComanda = " . $c['id_registro']);
            $c['productos'] = $productos_comanda;
            $c['generada'] = strtotime($c['generada']);
            $comandas[] = $c;
        }
        echo json_encode($comandas);
        exit;
    break;
    case 'cobrar_comanda':
        $id_registro = isset($data['id_registro'] ) ? $data['id_registro']  : 0;
        $comanda = array(
            'OrdenPagada' => 1,
            'IdUsuarioModifica' => $_SESSION['id'],
            'FechaModifica' => date('Y-m-d H:i:s')
        );
        $id_comanda = $db->updateById('tb_comandas', $comanda, 'id', $id_registro);
        if ($id_comanda !== false){
            $response = array ('id_registro' => $id_comanda, 'message' => 'La comanda ha sido cerrada');
            $c = $data;
            // echo '<pre>';print_r($c);echo'</pre>';
            // update a la comanda para marcarla como cobrada/cerrada
            require ROOTPATH . 'lib/printer.php'; 
        } else {
            $error = $db->executeError();
            $response = array ('id_registro' => 0, 'message' => 'Error al cerrar: ' . $error['db_message']);
        }
        echo json_encode($response);
        exit;
    break;
    default: echo 'No valido: ' . $accion; print_array($data); exit;
}    