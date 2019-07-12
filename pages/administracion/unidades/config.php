<?php
defined('_PUBLIC_ACCESS') or die();

$table = 'tb_unidades';
$seccion = 'unidades';

$titulo = 'Catálogo de Unidades de Medida';
// Para la lista
$columns_list = array(
    'id' => 'id',
    'nombre' => 'Unidad',
    'abreviacion' => 'Abreviacion',
    'id_tipo_medida' => 'IdTipoMedida',
    'descripcion' => 'Descripcion'
);
$conditions_list = array(
    'Estatus = 1'
);

// Para el editor
$titulo_editor = 'Agregar/Editar Unidades de Medida';
$id = (isset($_GET['id']) && $_GET['id'] > 0) ? $_GET['id'] : 0;
$conditions_item = array(
    'id =' . $id
);
$columns_item = array(
    'id' => 'id',
    'nombre' => 'Unidad',
    'abreviacion' => 'Abreviacion',
    'id_tipo_medida' => 'IdTipoMedida',
    'descripcion' => 'Descripcion'
);



// Inicializamos las variables para el editor
$item = array();
foreach($columns_item as $key => $column){
    $item[$key] = '' ;
}


$tipos_medida = $db->select('tb_ctlg_tipos_medida', array(
    'id' => 'id',
    'nombre' => "CONCAT(Nombre,' (',IFNULL(Descripcion,''),')')"
), array('Estatus = 1'));

$tipos_medida_indexed = array();
foreach($tipos_medida as $r){
    $tipos_medida_indexed[$r['id']] = $r['nombre'];
}