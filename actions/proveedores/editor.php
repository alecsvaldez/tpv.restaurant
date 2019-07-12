<?php
defined('_PUBLIC_ACCESS') or die();

// $db->show_sql = true; // prevent to wun query
$table = 'tb_proveedores';
$id = (isset($_POST['id']) && $_POST['id'] > 0) ? $_POST['id'] : 0;

// Translate $_POST to Table Columns in $data
$data = array(
    'Proveedor' => $_POST['nombre'],
    'Descripcion' => $_POST['descripcion'],
    'Contacto' => $_POST['contacto'],
    'Correo' => $_POST['correo'],
    'Telefono' => $_POST['telefono'],
    'Celular' => $_POST['celular'],
    'RFC' => $_POST['rfc'],
    'RazonSocial' => $_POST['razon_social'],
    'Direccion' => $_POST['direccion'],
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
    header('Location: /administracion/proveedores');
} else {
    $error = $db->executeError();
    sessionMessage('error', $error['db_message'], 'Ocurrió un error al registrar la información');
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}
exit;