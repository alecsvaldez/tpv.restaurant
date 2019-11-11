<?php
defined('_PUBLIC_ACCESS') or die();

// $db->show_sql = true; // prevent to wun query
$table = 'tb_cortes_caja';
$id = (isset($_POST['id']) && $_POST['id'] > 0) ? $_POST['id'] : 0;

// Obtenemos los ids de las comandas
$ids_comandas = array();
foreach($_POST['corte'] AS $idc => $check){
    if ($check == 'on'){
        $ids_comandas[] = $idc;
    }
}

// Translate $_POST to Table Columns in $data
$data = array(
    'FechaCorteInicio' => date('Y-m-d'),
    'FechaCorteFin' => date('Y-m-d'),
    'BalanceInicio' => $_POST['balance_inicial'],
    'Efectivo' => $_POST['efectivo'],
    'Tarjeta' => $_POST['tarjeta'],
    'Servicio' => $_POST['servicio'],
    'Gastos' => $_POST['gastos'],
    'Retiro' => $_POST['retiro'],
    'Fondo' => $_POST['fondo'],
    'EfectivoIngreso' => $_POST['efectivo-ingreso'],
    'BalanceFin' => $_POST['balance_final'],
    'Faltante' => $_POST['faltante'],
);
if ($id > 0) {
    $data['IdUsuarioModifica'] = $_SESSION['id'];
    $data['FechaModifica'] = date('Y-m-d H:i:s');
    $id = $db->updateById($table, $data, 'id', $id);
} else {
    $data['IdUsuarioCrea'] = $_SESSION['id'];
    $data['FechaCrea'] = date('Y-m-d H:i:s');
    $id = $db->insert($table, $data);
}

if ($id !== false) {

    //update a las comandas
    $db->updateById('tb_comandas', array('IdCorteCaja' => $id), 'id', $ids_comandas);

    sessionMessage('success', 'Los datos se han guardado.');
    header('Location: ' . $site_url . '/corte');
} else {
    $error = $db->executeError();
    sessionMessage('error', $error['db_message'], 'Ocurrió un error al registrar la información');
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

exit;

