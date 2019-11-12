<?php
defined('_PUBLIC_ACCESS') or die();

// $db->show_sql = true; // prevent to wun query
$table = 'tb_tipo_productos';
$id = (isset($_POST['id']) && $_POST['id'] > 0) ? $_POST['id'] : 0;

// Translate $_POST to Table Columns in $data
$data = array(
    'Tipo' => $_POST['nombre'],
    'IdCategoriaProducto' => $_POST['id_categoria'],
    'Descripcion' => $_POST['descripcion']
);
if ($id > 0){
    $id = $db->updateById($table, $data, 'id', $id);
} else {
    $id = $db->insert($table, $data);
}

if ($id !== false){
    sessionMessage('success', 'Los datos se han guardado.');
    header('Location: ' . $site_url . 'administracion/tipos/productos');
} else {
    $error = $db->executeError();
    sessionMessage('error', $error['db_message'], 'Ocurrió un error al registrar la información');
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}
exit;