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
    case 'productos':
        $id_menu = $_GET['id'];
        $productos = $db->get("SELECT
                p.id AS id
                , Producto AS nombre
                , Categoria AS categoria
                , PrecioBase AS precio
                , CostoBase AS costo
            FROM tb_productos p
                INNER JOIN tb_cat_productos c ON c.id = p.IdCategoria
                LEFT  JOIN tb_menus_productos m ON m.IdProducto = p.id AND m.IdMenu = $id_menu AND m.Estatus = 1
            WHERE p.Estatus = 1
                AND m.id IS NULL");
        echo json_encode($productos);
        exit;
    break;
    case 'quitar_producto':
        $id_registro = isset($_GET['id'] ) ? $_GET['id']  : 0;
        $registro = array(
            'Estatus' => 0,
            'IdUsuarioModifica' => $_SESSION['id'],
            'FechaModifica' => date('Y-m-d H:i:s')
        );
        $id_registro = $db->updateById('tb_menus_productos', $registro, 'id', $id_registro);
        if ($id_registro !== false){
            $response = array ('id_registro' => $id_registro, 'message' => 'El producto ha sido removido');
        } else {
            $error = $db->executeError();
            $response = array ('id_registro' => 0, 'message' => 'Error al quitar: ' . $error['db_message']);
        }
        echo json_encode($response);
        exit;
    break;
    default: echo 'No valido';print_array($data); exit;
}