<?php
defined('_PUBLIC_ACCESS') or die();

$table = 'tb_ingredientes';
$seccion = 'ingredientes';

$titulo = 'Ingredientes';
// Para la lista
$columns_list = array(
    'id' => 'id',
    'nombre' => 'Ingrediente',
    'id_categoria' => 'IdCategoria',
    'id_unidad' => 'IdUnidad',
    'id_unidad_entrada' => 'IdUnidadEntrada',
    'factor_conversion' => 'FactorConversion',
    'id_unidad_salida' => 'IdUnidadSalida',
    'descripcion' => 'Descripcion'
);
$conditions_list = array(
    'Estatus = 1'
);

// Para el editor
$titulo_editor = 'Agregar/Editar Ingredientes';
$id = (isset($_GET['id']) && $_GET['id'] > 0) ? $_GET['id'] : 0;
$conditions_item = array(
    'id =' . $id
);
$columns_item = array(
    'id' => 'id',
    'nombre' => 'Ingrediente',
    'id_categoria' => 'IdCategoria',
    'id_unidad' => 'IdUnidad',
    'id_unidad_entrada' => 'IdUnidadEntrada',
    'id_unidad_salida' => 'IdUnidadSalida',
    'factor_conversion' => 'FactorConversion',
    'descripcion' => 'Descripcion'
);

// Inicializamos las variables para el editor
$item = array();
foreach($columns_item as $key => $column){
    $item[$key] = '' ;
}

$categorias = $db->select('tb_cat_ingredientes', array(
    'id' => 'id',
    'nombre' => 'Categoria'
), array('Estatus = 1'));

$tipos_medida = $db->select('tb_unidades', array(
    'id' => 'id',
    'nombre' => "CONCAT(Unidad,' (',IFNULL(Abreviacion,''),')')"
), array('Estatus = 1'));

$categorias_indexed = array();
foreach($categorias as $r){
    $categorias_indexed[$r['id']] = $r['nombre'];
}
$tipos_medida_indexed = array();
foreach($tipos_medida as $r){
    $tipos_medida_indexed[$r['id']] = $r['nombre'];
}