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
            id AS id_registro
            , CONCAT(Comanda, '-', id) AS nombre
            , CONCAT('C-', id) AS comanda
            , IdAtiende AS id_usuario
            , 0 AS activa
            , FechaCrea AS generada
        FROM tb_comandas
        WHERE Estatus = 1
            AND OrdenCerrada = 0
            AND IdTicket = 0
        ORDER BY id");

        $comandas = array();
        foreach($comandas_db as &$c){
            // Por cada comanda vamos por los productos
            $productos_comanda = $db->get("SELECT
                cp.id AS id_registro
                , cp.IdProducto AS id_producto
                , p.Producto AS nombre
                -- , c.Categoria AS categoria
                , cp.Cantidad AS cantidad
            FROM tb_comandas_productos cp
                INNER JOIN tb_productos p ON p.id = cp.IdProducto
                -- INNER JOIN tb_cat_productos c ON c.id = p.IdCategoria
            WHERE cp.Estatus = 1 
                AND cp.IdComanda = " . $c['id_registro']);
            $c['productos'] = $productos_comanda;
            $c['generada'] = strtotime($c['generada']);
            $comandas[] = $c;
        }
        echo json_encode($comandas);
        exit;
    break;
    default: echo 'No valido';print_array($data); exit;
}    