<?php
defined('_PUBLIC_ACCESS') or die();

$table = 'tb_inventario';
$seccion = 'inventario';
$titulo = 'Inventario';

//Consulta en index

$proveedores = $db->select('tb_proveedores', array(
    'id' => 'id',
    'nombre' => 'Proveedor'
), array('Estatus = 1'));


$ingredientes = $db->select('tb_ingredientes', array(
    'id' => 'id',
    'nombre' => 'Ingrediente'
), array('Estatus = 1'));

$ingredientes_indexed = array();
foreach($ingredientes as $r){
    $ingredientes_indexed[$r['id']] = $r['nombre'];
}

$unidades = $db->select('tb_unidades', array(
    'id' => 'id',
    'nombre' => "CONCAT(Unidad,' (',IFNULL(Abreviacion,''),')')"
), array('Estatus = 1'));

$unidades_indexed = array();
foreach($unidades as $r){
    $unidades_indexed[$r['id']] = $r['nombre'];
}