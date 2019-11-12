<?php
defined('_PUBLIC_ACCESS') or die();

// $db->show_sql = true; // prevent to wun query
$table = 'tb_compras';
$id = (isset($_POST['id']) && $_POST['id'] > 0) ? $_POST['id'] : 0;

// Translate $_POST to Table Columns in $data
$data = array(
    'Foliocompra' => $_POST['folio'],
    'IdProveedor' => $_POST['id_proveedor'],
    'FechaCompra' => $_POST['fecha_compra'],
    'FechaEntrega' => $_POST['fecha_entrega'],
    'OrdenCerrada' => isset($_POST['orden_cerrada']) ? $_POST['orden_cerrada'] : 0,
    'SaldoTotal' => $_POST['saldo_total'],
    'SaldoPagado' => $_POST['saldo_pagado'],
    'SaldoPendiente' => $_POST['saldo_pendiente'],
    'Comentarios' => $_POST['comentarios'],
);

echo '<pre>';print_r($data);echo'</pre>';exit;

if ($id > 0){
    $data['IdUsuarioModifica'] = $_SESSION['id'];
    $data['FechaModifica'] = date('Y-m-d H:i:s');
    $id = $db->updateById($table, $data, 'id', $id);
} else {
    $data['IdUsuarioCrea'] = $_SESSION['id'];
    $data['FechaCrea'] = date('Y-m-d H:i:s');
    $id = $db->insert($table, $data);
}

if ($id > 0 && isset($_POST['items']) &&  count($_POST['items']) > 0 ){
    // Ahora registramos los ingredientes
    $table = 'tb_compras_detalle';
    foreach($_POST['items'] as $index => $val){
        $data = array(
            'IdCompra' => $id,
            'IdItem' => $val['id_item'],
            'IdUnidad' => $val['id_unidad'],
            'IdUnidadOriginal' => $val['id_unidad_original'], // Se guarda esta unidad para después permitir cambio de unidades en compra con equivalencias
            'Precio' => $val['precio'],
            'Cantidad' => $val['cantidad'],
            'Total' => $val['total'],
            'id' => $val['id_registro']
        );
        // Las fechas no se guardan en el detalle, ya que se duplicarían con la compra.
        if ($data['id'] > 0){
            // $data['IdUsuarioModifica'] = $_SESSION['id'];
            // $data['FechaModifica'] = date('Y-m-d H:i:s');
            $data['id'] = $db->updateById($table, $data, 'id', $data['id']);
        } else {
            // $data['IdUsuarioCrea'] = $_SESSION['id'];
            // $data['FechaCrea'] = date('Y-m-d H:i:s');
            $data['id'] = $db->insert($table, $data);
        }
    }
}

if ($id !== false){
    sessionMessage('success', 'Los datos se han guardado.');
    header('Location: ' . $site_url . 'compras');
} else {
    $error = $db->executeError();
    sessionMessage('error', $error['db_message'], 'Ocurrió un error al registrar la información');
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}
exit;