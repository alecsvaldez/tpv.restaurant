<?php
defined('_PUBLIC_ACCESS') or die();
include_once 'config.php';

$item = $db->find('tb_productos',array(
    'id' => 'id',
    'nombre' => 'Producto',
    'id_categoria' => 'IdCategoria',
    'id_tipo_producto' => 'IdTipoProducto',
    'costo' => 'CAST(CostoBase AS DECIMAL(6,2))',
    'precio' => 'CAST(PrecioBase AS DECIMAL(6,2))',
    'descripcion' => 'Descripcion',
), array('id = ' . $id));

echo '<pre>';print_r($item);echo'</pre>';
