<?php
namespace TPV;
defined('_PUBLIC_ACCESS') or die();

header('Content-Type: application/json');
switch($accion){
    case 'detalle': 
        $data = $db->find('tb_compras', 
            array(
                'id' => 'id',
                'folio' => 'FolioCompra',
                'id_proveedor' => 'IdProveedor',
                'fecha_compra' => "IF(FechaCompra <> '0000-00-00', FechaCompra,'')",
                'fecha_entrega' => "IF(FechaEntrega <> '0000-00-00', FechaEntrega,'')",
                'orden_cerrada' => 'OrdenCerrada',
                'saldo_total' => 'SaldoTotal',
                'saldo_pagado' => 'SaldoPagado',
                'saldo_pendiente' => 'SaldoPendiente',
                'comentarios' => 'Comentarios',
            ), 
             array(
                'id =' . $id
            ));
        $data['items'] = $db->select('tb_compras_detalle',array(
            'id_registro' => 'id',
            'id_item' => 'IdItem',
            'item' => 'Item',
            'id_unidad' => 'IdUnidad',
            'unidad' => 'Unidad',
            'precio' => 'Precio',
            'cantidad' => 'Cantidad',
            'id_unidad_salida' => 'IdUnidadSalida',
            'unidad_salida' => 'UnidadSalida',
            'total' => 'Total',
        ), array(
            'IdCompra = ' . $id,
            'Estatus = 1'
        ));
    break;
    default: echo 'No valido';print_array($_GET); exit;
}
echo json_encode($data);


