<?php
defined('_PUBLIC_ACCESS') or die();

// $db->show_sql = true; // prevent to wun query
$table = 'tb_ingredientes';
$id = (isset($_POST['id']) && $_POST['id'] > 0) ? $_POST['id'] : 0;

// Translate $_POST to Table Columns in $data
$data = array(
    'Ingrediente' => $_POST['nombre'],
    'Descripcion' => $_POST['descripcion'],
    'IdCategoria' => $_POST['id_categoria'],
    'IdUnidad' => $_POST['id_unidad'],
    'IdUnidadEntrada' => $_POST['id_unidad_entrada'],
    'FactorConversion' => $_POST['factor_conversion'],
    'IdUnidadSalida' => $_POST['id_unidad_salida']
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
    header('Location: ' . $site_url . '/administracion/ingredientes');
} else {
    $error = $db->executeError();
    sessionMessage('error', $error['db_message'], 'Ocurrió un error al registrar la información');
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}
exit;