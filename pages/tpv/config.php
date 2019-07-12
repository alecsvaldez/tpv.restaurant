<?php
defined('_PUBLIC_ACCESS') or die();

$table = 'tb_tpv';
$seccion = 'tpv';
$titulo = 'TPV';

// Por el momento vamos a dejar activo unicamente el menú de cenas. Esta opcion después se moverá a un combo dentro del TPV
$id_menu = 3;
$menus = $db->select('tb_menus', array(
    'id' => 'id',
    'nombre' => "Menu"
), array('Estatus = 1', 'id = '. $id_menu));


$mesas = $db->select('tb_mesas', array(
    'id' => 'id',
    'nombre' => "CONCAT(Mesa, ' ', Numero)"
), array('Estatus = 1'));

$mesas_indexed = array();
foreach($mesas as $r){
    $mesas_indexed[$r['id']] = $r['nombre'];
}



