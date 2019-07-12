<?php
namespace TPV;
defined('_PUBLIC_ACCESS') or die();

header('Content-Type: application/json');

switch($accion){
    case 'detalle': 
        $data = $db->first("SELECT 
            i.id
            , i.Ingrediente AS nombre
            , ci.Categoria AS categoria
            , u.id AS id_unidad
            , u.Abreviacion AS unidad
        FROM tb_ingredientes i
            INNER JOIN tb_cat_ingredientes ci ON  ci.id = i.IdCategoria
            INNER JOIN tb_unidades u ON u.id = i.IdUnidad
        WHERE i.Estatus = 1 
        AND i.id = :id ", array('id' => $id ));
    break;
    default: echo 'No valido';print_array($_GET); exit;
}
echo json_encode($data);

