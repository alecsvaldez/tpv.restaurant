<?php
defined('_PUBLIC_ACCESS') or die();

$table = 'tb_menus';
$seccion = 'menus';
$subseccion = 'productos';

$titulo_editor = 'Productos del menú ';

// Cuando se entra a una preparación, inmediatamente se crea el registro en BDD, esto para tener un mejor control de cambios.
// Lo mismo sucede cuando se agrea o elimina un ingrediente. Todo lo que se muestra en pantalla tiene un registro en BDD


$id_menu = (isset($_GET['id']) && $_GET['id'] > 0) ? $_GET['id'] : 0;

//primero vamos a buscar el registro de preparacion:
$conditions_item = array(
    'id =' . $id_menu
);
$columns_item = array(
    'id' => 'id',
    'nombre' => 'Menu',
    'descripcion' => 'Descripcion'
);
$item = $db->find($table, $columns_item, $conditions_item );


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

$productos_indexed = array();
foreach($productos as $r){
    $productos_indexed[$r['id']] = $r['nombre'];
}

$item['productos'] = $db->get("SELECT
                m.id AS id
                , p.id AS id_producto
                , Producto AS producto
                , Categoria AS categoria
                , Precio AS precio
                , Costo AS costo
            FROM tb_productos p
                INNER JOIN tb_cat_productos c ON c.id = p.IdCategoria
                INNER JOIN tb_menus_productos m ON m.IdProducto = p.id
            WHERE p.Estatus = 1
                AND m.Estatus = 1
                AND m.IdMenu = $id_menu");