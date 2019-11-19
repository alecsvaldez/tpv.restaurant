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
$items = json_decode(json_decode($_POST['items']), true);
// echo '<pre>';print_r($data);echo'</pre>';
// echo '<pre>';print_r($items);echo'</pre>';

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
    foreach($items as $index => $val){
        $data = array(
            'IdCompra' => $id,
            'IdItem' => $val['id_item'],
            'Item' => $val['item'],
            'IdUnidad' => $val['id_unidad_entrada'],
            'Unidad' => $val['unidad_entrada'],
            'IdUnidadSalida' => $val['id_unidad_salida'], // Se guarda esta unidad para después permitir cambio de unidades en compra con equivalencias
            'UnidadSalida' => $val['unidad_salida'], // Se guarda esta unidad para después permitir cambio de unidades en compra con equivalencias
            'Precio' => $val['precio'],
            'Cantidad' => $val['cantidad'],
            'FactorConversion' => $val['conversion'],
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

        if ($_POST['orden_cerrada'] == 1){
            // Ahora vamos a guardar a inventario
            $inventario = $db->find('tb_inventario', array('id'=>'id', 'existencia' => 'Existencia'), array('IdTipoItem' => 1, 'IdItem' => $val['id_item'] ));
            // echo '<pre>';print_r($inventario);echo'</pre>';
            $movimiento = $val['cantidad'] * $val['conversion'];
    
            if ($inventario && $inventario['id'] > 0){
                $existencia = $inventario['existencia'] + ($val['cantidad'] * $val['conversion']);
                $id_inventario = $db->updateById('tb_inventario', array(
                    'TipoMovimiento' => 1, // 1 = entrada, 0 = salida, 2/-1 = merma
                    'Movimiento' => $movimiento,
                    'Existencia' => $existencia,
                    'UltimoIngreso' => date('Y-m-d H:i:s'),
                    'IdUltimoIngreso' => $id,
                    'Comentarios' => 'Compra ' . $id . '-' . $_POST['folio']
                ), 'id', $inventario['id'] );
            } else {
                $id_inventario = $db->insert('tb_inventario', array(
                    'IdTipoItem' => 1,
                    'IdItem' => $val['id_item'],
                    'IdUnidad' => $val['id_unidad_salida'],
                    'Min' => 0,
                    'TipoMovimiento' => 1,
                    'Movimiento' => $movimiento,
                    'Existencia' => $movimiento,
                    'UltimoIngreso' => date('Y-m-d H:i:s'),
                    'IdUltimoIngreso' => $id,
                    'Comentarios' => 'Compra ' . $id . '-' . $_POST['folio']
                ));
            }
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