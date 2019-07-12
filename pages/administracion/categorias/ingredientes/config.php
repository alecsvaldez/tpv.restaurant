<?php
defined('_PUBLIC_ACCESS') or die();

$table = 'tb_cat_ingredientes';
$seccion = 'categorias';
$subseccion = 'ingredientes';

$titulo = 'Categorías de Ingredientes';
// Para la lista
$columns_list = array(
    'id' => 'id',
    'nombre' => 'Categoria',
    'descripcion' => 'Descripcion'
);
$conditions_list = array(
    'id > 0',
    "Categoria <> ''"
);


// Para el editor

$titulo_editor = 'Agregar/Editar Categorías de Ingredientes';
$id = (isset($_GET['id']) && $_GET['id'] > 0) ? $_GET['id'] : 0;
$conditions_item = array(
    'id =' . $id
);
$columns_item = array(
    'id' => 'id',
    'category_name' => 'Categoria',
    'description' => 'Descripcion'
);

// Inicializamos las variables para el editor
$item = array();
foreach($columns_item as $key => $column){
    $item[$key] = '' ;
}
