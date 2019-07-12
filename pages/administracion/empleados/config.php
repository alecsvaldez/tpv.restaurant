<?php
defined('_PUBLIC_ACCESS') or die();

$table = 'tb_empleados';
$seccion = 'empleados';
$titulo = 'Empleados';
// Para la lista
$columns_list = array(
    'id' => 'id',
    'nombre' => "CONCAT(Nombre, ' ', Paterno, ' ', Materno )",
    'correo' => 'Correo',
    'telefono' => 'Telefono',
    'celular' => 'Celular',
    'fecha_inicio' => 'FechaInicio',
    'fecha_fin' => 'FechaFin',
    'activo' => 'Activo',
);
$conditions_list = array(
    'Estatus = 1'
);

// Para el editor
$titulo_editor = 'Agregar/Editar Empleados';
$id = (isset($_GET['id']) && $_GET['id'] > 0) ? $_GET['id'] : 0;
$conditions_item = array(
    'id =' . $id
);
$columns_item = array(
    'id' => 'id',
    'nombre' => 'Nombre',
    'paterno' => 'Paterno',
    'materno' => 'Materno',
//    '' => 'IdPuesto',
    'direccion' => 'Direccion',
    'telefono' => 'Telefono',
    'celular' => 'Celular',
    'correo' => 'Correo',
    'fecha_inicio' => "IF(FechaInicio <> '0000-00-00', FechaInicio,'')",
    'fecha_fin' => "IF(FechaFin <> '0000-00-00', FechaFin, '')",
    'activo' => 'Activo',

);

// Inicializamos las variables para el editor
$item = array();
foreach($columns_item as $key => $column){
    $item[$key] = '' ;
}

