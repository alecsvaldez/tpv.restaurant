<?php
defined('_PUBLIC_ACCESS') or die();

$table = 'tb_proveedores';
$seccion = 'proveedores';
$titulo = 'Proveedores';
// Para la lista
$columns_list = array(
    'id' => 'id',
    'nombre' => 'Proveedor',
    'descripcion' => 'Descripcion',
    'contacto' => 'Contacto',
    'correo' => 'Correo',
    'telefono' => 'Telefono',
    'celular' => 'Celular',
    'razon_social' => 'RazonSocial',  
);
$conditions_list = array(
    'Estatus = 1'
);

// Para el editor
$titulo_editor = 'Agregar/Editar Proveedores';
$id = (isset($_GET['id']) && $_GET['id'] > 0) ? $_GET['id'] : 0;
$conditions_item = array(
    'id =' . $id
);
$columns_item = array(
    'id' => 'id',
    'nombre' => 'Proveedor',
    'descripcion' => 'Descripcion',
    'contacto' => 'Contacto',
    'correo' => 'Correo',
    'telefono' => 'Telefono',
    'celular' => 'Celular',
    'rfc' => 'RFC',
    'razon_social' => 'RazonSocial',
    'direccion' => 'Direccion',
);

// Inicializamos las variables para el editor
$item = array();
foreach($columns_item as $key => $column){
    $item[$key] = '' ;
}

