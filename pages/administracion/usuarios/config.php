<?php
defined('_PUBLIC_ACCESS') or die();

$table = 'tb_usuarios';
$seccion = 'usuarios';
$titulo = 'Usuarios';
// Para la lista
$columns_list = array(
    'id' => 'id',
    'id_empleado' => 'IdEmpleado',
    'nombre' => "CONCAT(IFNULL(Nombre,''), ' ', IFNULL(Apellidos,''))",
    'usuario' => "Usuario",
    'correo' => 'Correo',
    'telefono' => 'Telefono',
    'activo' => 'Activo',
    'id_rol' => 'IdRol',
);
$conditions_list = array(
    'Estatus = 1'
);

// Para el editor
$titulo_editor = 'Agregar/Editar Usuarios';
$id = (isset($_GET['id']) && $_GET['id'] > 0) ? $_GET['id'] : 0;
$conditions_item = array(
    'id =' . $id
);
$columns_item = array(
    'id' => 'id',
    'usuario' => 'Usuario',
    'contrasena' => 'Contrasena',
    'pin' => 'Pin',
    'nombre' => 'Nombre',
    'apellidos' => 'Apellidos',
    'correo' => 'Correo',
    'telefono' => 'Telefono',
    'activo' => 'Activo',
    'id_rol' => 'IdRol',
    'es_empleado' => 'EsEmpleado',
    'id_empleado' => 'IdEmpleado',
);

// Inicializamos las variables para el editor
$item = array();
foreach($columns_item as $key => $column){
    $item[$key] = '' ;
}

$empleados = $db->select('tb_empleados', array(
    'id' => 'id',
    'nombre' => "CONCAT(Nombre, ' ', Paterno, ' ', Materno )",
), array('Estatus = 1'));

$roles = $db->select('tb_roles', array(
    'id' => 'id',
    'nombre' => "CONCAT(Descripcion,' (',Rol,')')"
), array('Estatus = 1'));

$roles_indexed = array();
foreach($roles as $r){
    $roles_indexed[$r['id']] = $r['nombre'];
}