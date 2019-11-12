<?php
namespace TPV;
defined('_PUBLIC_ACCESS') or die();

header('Content-Type: application/json');

switch($accion){
    case 'detalle': 
        // Usado en: compras/editor
        $data = $db->first("SELECT 
            i.id AS id_item
            , i.Ingrediente AS item
            , ci.id AS id_categoria
            , ci.Categoria AS categoria
            , ue.id AS id_unidad_entrada
            , ue.Abreviacion AS unidad_entrada
            , i.FactorConversion AS conversion
            , us.id AS id_unidad_salida
            , us.Abreviacion AS unidad_salida
        FROM tb_ingredientes i
            INNER JOIN tb_cat_ingredientes ci ON  ci.id = i.IdCategoria
            INNER JOIN tb_unidades ue ON ue.id = i.IdUnidadEntrada
            INNER JOIN tb_unidades us ON us.id = i.IdUnidadSalida
        WHERE i.Estatus = 1 
        AND i.id = :id ", array('id' => $id ));
    break;
    default: echo 'No valido';print_array($_GET); exit;
}
echo json_encode($data);

