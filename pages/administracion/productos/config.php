<?php
defined('_PUBLIC_ACCESS') or die();

$table = 'tb_productos';
$seccion = 'productos';
$titulo = 'Productos';
// Para la lista
$columns_list = array(
    'id' => 'id',
    'nombre' => 'Producto',
    'id_categoria' => 'IdCategoria',
    'id_tipo_producto' => 'IdTipoProducto',
    'descripcion' => 'Descripcion'
);
$conditions_list = array(
    'Estatus = 1'
);

// Para el editor
$titulo_editor = 'Agregar/Editar Productos';
$id = (isset($_GET['id']) && $_GET['id'] > 0) ? $_GET['id'] : 0;
$conditions_item = array(
    'id =' . $id
);
$columns_item = array(
    'id' => 'id',
    'nombre' => 'Producto',
    'id_categoria' => 'IdCategoria',
    'id_tipo_producto' => 'IdTipoProducto',
    'costo' => 'CAST(CostoBase AS DECIMAL(6,2))',
    'precio' => 'CAST(PrecioBase AS DECIMAL(6,2))',
    'descripcion' => 'Descripcion',
);

// Inicializamos las variables para el editor
$item = array();
foreach($columns_item as $key => $column){
    $item[$key] = '' ;
}

if ($id > 0){
    $foto = false;
    if (file_exists(ROOTPATH . '/uploads/productos/' . $id . '.jpg')){
        $foto = '/uploads/productos/' . $id . '.jpg';
        $foto_size = humanFileSize(filesize(ROOTPATH . $foto));
        $foto .= '?r=' . rand();
    }
} else {
    $foto = '';
}

$categorias = $db->select('tb_cat_productos', array(
    'id' => 'id',
    'nombre' => 'Categoria'
), array('Estatus = 1'));

$categorias_indexed = array();
foreach($categorias as $r){
    $categorias_indexed[$r['id']] = $r['nombre'];
}

$tipos_producto = $db->select('tb_tipo_productos', array(
    'id' => 'id',
    'nombre' => 'Tipo'
), array('Estatus = 1'));


$tipos_producto_indexed = array();
foreach($tipos_producto as $r){
    $tipos_producto_indexed[$r['id']] = $r['nombre'] ;
}
