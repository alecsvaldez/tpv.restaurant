<?php
defined('_PUBLIC_ACCESS') or die();

$table = 'tb_cortes_caja';
$seccion = 'corte';
$titulo = 'Corte de Caja';

// para el editor
$titulo_editor = 'Nuevo Corte de Caja';

$id = (isset($_GET['id']) && $_GET['id'] > 0) ? $_GET['id'] : 0;
$conditions_item = array(
    'id =' . $id
);
