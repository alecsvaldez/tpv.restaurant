<?php
defined('_PUBLIC_ACCESS') or die();

$table = 'tb_preparaciones';
$seccion = 'productos';
$subseccion = 'preparacion';

$titulo_editor = 'Preparación de ';

// Cuando se entra a una preparación, inmediatamente se crea el registro en BDD, esto para tener un mejor control de cambios.
// Lo mismo sucede cuando se agrea o elimina un ingrediente. Todo lo que se muestra en pantalla tiene un registro en BDD


$id_producto = (isset($_GET['id']) && $_GET['id'] > 0) ? $_GET['id'] : 0;

//primero vamos a buscar el registro de preparacion:
$conditions_item = array(
    'IdProducto =' . $id_producto
);
$columns_item = array(
    'id' => 'id',
    'id_producto' => 'IdProducto',
    'instrucciones' => 'Instrucciones'
);
$item = $db->find($table, $columns_item, $conditions_item );
// Si no hay registro, lo creamos
if (!$item){
    $data = array(
        'IdProducto' => $id_producto,
        'IdUsuarioCrea' => $_SESSION['id'],
        'FechaCrea' => date('Y-m-d H:i:s')
    );
    $id = $db->insert($table, $data);

    $item = $db->find($table, $columns_item, array('id = '. $id) );
} else {
    $id = $item['id'];
}

if ($id > 0){
    $item = $db->find($table,$columns_item, $conditions_item);
    // También necesitamos los ingredientes de la tabla. Vamos por ellos
    // $db->enableShowSql();
    $item['ingredientes'] = $db->select('tb_preparaciones_ingredientes',array(
        'id' => 'id',
        'id_ingrediente' => 'IdIngrediente',
        'id_unidad' => 'IdUnidad',
        'cantidad' => 'Cantidad',
    ), array(
        'IdPreparacion = ' . $id
    ));
    


} else {
    echo 'Error al obtener el registro de la preparación!  <-- esto hay que mejorarlo'; exit;
}

if ($id > 0){
    $producto = $db->find('tb_productos',array(
        'id' => 'id',
        'nombre' => 'Producto',
        'descripcion' => 'Descripcion'
    ), array(
        'id =' . $id_producto
    ));
}


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