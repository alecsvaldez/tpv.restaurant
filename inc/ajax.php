<?php
namespace TPV;
define('_PUBLIC_ACCESS',1);
require_once 'init.php';

$file_path = ROOTPATH . 'actions/';
$seccion = '';
$subseccion = '';
$accion = '';
$id = null;
if (!empty($_REQUEST['seccion'])){
    $seccion .= strtolower(str_replace('-','_',$_GET['seccion'])) . '/';
}
if (!empty($_REQUEST['subseccion'])){
    $subseccion .= strtolower(str_replace('-','_',$_GET['subseccion'])) . '/';
}      
if (!empty($_GET['accion'])){
    $accion = strtolower(str_replace('-','_',$_GET['accion'])) ;
}      
if (isset($_GET['id'])){
    $id = $_GET['id'];
}      
$file = $file_path . $seccion . $subseccion . 'ajax.php';

if (!file_exists($file)){
    die();
}
require_once $file;

