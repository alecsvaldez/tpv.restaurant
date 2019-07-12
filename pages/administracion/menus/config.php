<?php
defined('_PUBLIC_ACCESS') or die();

$table = 'tb_menus';
$seccion = 'menus';
$titulo = 'Menús';
// Para la lista
$columns_list = array(
    'id' => 'id',
    'nombre' => 'Menu',
    'id_categoria' => 'IdCategoria',
    'descripcion' => 'Descripcion'
);
$conditions_list = array(
    'Estatus = 1'
);

// Para el editor
$titulo_editor = 'Agregar/Editar Menus';
$id = (isset($_GET['id']) && $_GET['id'] > 0) ? $_GET['id'] : 0;
$conditions_item = array(
    'id =' . $id
);
$columns_item = array(
    'id' => 'id',
    'nombre' => 'Menu',
    'descripcion' => 'Descripcion',
    'id_categoria' => 'IdCategoria'
);

// Inicializamos las variables para el editor
$item = array();
foreach($columns_item as $key => $column){
    $item[$key] = '' ;
}

$categorias = $db->select('tb_cat_menus', array(
    'id' => 'id',
    'nombre' => 'Categoria'
), array('Estatus = 1'));

$categorias_indexed = array();
foreach($categorias as $r){
    $categorias_indexed[$r['id']] = $r['nombre'];
}

