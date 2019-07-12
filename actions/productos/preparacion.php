<?php
defined('_PUBLIC_ACCESS') or die();

// $db->show_sql = true; // prevent to wun query
$table = 'tb_preparaciones';
$id = (isset($_POST['id']) && $_POST['id'] > 0) ? $_POST['id'] : 0;

// Translate $_POST to Table Columns in $data
$data = array(
    'IdProducto' => $_POST['id_producto'],
    'Instrucciones' => $_POST['instrucciones'],
);

if ($id > 0){
    $data['IdUsuarioModifica'] = $_SESSION['id'];
    $data['FechaModifica'] = date('Y-m-d H:i:s');
    $id = $db->updateById($table, $data, 'id', $id);
}

if ($id > 0 && isset($_POST['ingrediente']) &&  count($_POST['ingrediente']) > 0 ){
    // Ahora registramos los ingredientes
    $table = 'tb_preparaciones_ingredientes';
    foreach($_POST['ingrediente'] as $id_ingrediente => $val){
        $data = array(
            'IdPreparacion' => $id,
            'IdProducto' => $_POST['id_producto'],
            'IdIngrediente' => $id_ingrediente,
            'IdUnidad' => $val['unidad'],
            'Cantidad' => $val['cantidad'],
            'id' => $val['id_registro']
        );

        if ($data['id'] > 0){
            $data['IdUsuarioModifica'] = $_SESSION['id'];
            $data['FechaModifica'] = date('Y-m-d H:i:s');
            $data['id'] = $db->updateById($table, $data, 'id', $data['id']);
        } else {
            $data['IdUsuarioCrea'] = $_SESSION['id'];
            $data['FechaCrea'] = date('Y-m-d H:i:s');
            $data['id'] = $db->insert($table, $data);
        }
    }
}

if ($id !== false){
    sessionMessage('success', 'Los datos se han guardado.');
    header('Location: /administracion/productos');
} else {
    $error = $db->executeError();
    sessionMessage('error', $error['db_message'], 'Ocurrió un error al registrar la información');
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}
exit;