<?php
defined('_PUBLIC_ACCESS') or die();

// $db->show_sql = true; // prevent to wun query
$table = 'tb_usuarios';
$id = (isset($_POST['id']) && $_POST['id'] > 0) ? $_POST['id'] : 0;


// Translate $_POST to Table Columns in $data
$data = array(
    'Usuario' => $_POST['usuario'],
    'Contrasena' => $_POST['contrasena'],
    'Pin' => $_POST['pin'],
    'EsEmpleado' => isset($_POST['es_empleado']) ? $_POST['es_empleado'] : 0,
    'IdEmpleado' => isset($_POST['id_empleado']) ? $_POST['id_empleado'] : 0,
    'Nombre' => $_POST['nombre'],
    'Apellidos' => $_POST['apellidos'],
    'Telefono' => $_POST['telefono'],
    'Correo' => $_POST['correo'],
    'Activo' => isset($_POST['activo']) ? $_POST['activo'] : 0,
    'IdRol' => $_POST['id_rol'],
);

if ($id > 0){
    $data['IdUsuarioModifica'] = $_SESSION['id'];
    $data['FechaModifica'] = date('Y-m-d H:i:s');
    $id = $db->updateById($table, $data, 'id', $id);
} else {
    $data['IdUsuarioCrea'] = $_SESSION['id'];
    $data['FechaCrea'] = date('Y-m-d H:i:s');
    $id = $db->insert($table, $data);
}

if ($id !== false){
    sessionMessage('success', 'Los datos se han guardado.');
    header('Location: /administracion/usuarios');
} else {
    $error = $db->executeError();
    sessionMessage('error', $error['db_message'], 'Ocurrió un error al registrar la información');
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}
exit;