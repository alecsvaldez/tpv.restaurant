<?php
namespace TPV;
defined('_PUBLIC_ACCESS') or die();

header('Content-Type: application/json');
switch($accion){
    case 'tipos_producto': 
        $data = $db->select('tb_tipo_productos', array(
            'id' => 'id',
            'nombre' => 'Tipo'
        ), array('Estatus = 1' , 'IdCategoriaProducto = ' . $id));
        
    break;
    default: echo 'No valido';print_array($_GET); exit;
}
echo json_encode($data);

