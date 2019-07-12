<?php
defined('_PUBLIC_ACCESS') or die();

$table = 'tb_mesas';
$seccion = 'mesas';
$titulo = 'Mesas';
// Para la lista
$columns_list = array(
    'id' => 'id',
    'nombre' => 'Mesa',
    'descripcion' => 'Descripcion',
    'numero' => 'Numero',
    'cantidad' => 'Cantidad'
);
$conditions_list = array(
    'Estatus = 1'
);

// Para el editor
$titulo_editor = 'Agregar/Editar Mesas';
$id = (isset($_GET['id']) && $_GET['id'] > 0) ? $_GET['id'] : 0;
$conditions_item = array(
    'id =' . $id
);
$columns_item = array(
    'id' => 'id',
    'nombre' => 'Mesa',
    'descripcion' => 'Descripcion',
    'numero' => 'Numero',
    'cantidad' => 'Cantidad'
);

// Inicializamos las variables para el editor
$item = array();
foreach($columns_item as $key => $column){
    $item[$key] = '' ;
}
