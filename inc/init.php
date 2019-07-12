<?php
namespace TPV;

defined('_PUBLIC_ACCESS') or die();


if (file_exists( $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php' )){
    require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
} else {
    echo '<h3><b>config.php</b> no existe.</h3>';
    exit;
}

require_once INC . 'functions.php';

if (ENV == 'dev'){
    error_reporting(E_ALL);
} else {
    error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );
}

//require_once INC . 'Data.php';    // controlador global 
// Aqui se manejan las acciones

use db;
try {
    $db = new db();
} catch (PDOException  $e) {
    echo '¡Error al conectar con la base de datos!<br> ' . $e -> getMessage();
    die();
}

require_once INC . 'session.php';

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_REQUEST['action'])){
    $seccion = '/';
    $subseccion = '';
    if (!empty($_REQUEST['seccion'])){
        $seccion = '/' . $_REQUEST['seccion'] . '/';
    }
    if (!empty($_REQUEST['subseccion'])){
        $subseccion = $_REQUEST['subseccion'] . '/';
    }
    $action_file = ABSPATH . 'actions' . $seccion . $subseccion . $_REQUEST['action'] . '.php';
    if (file_exists($action_file)){
        require_once( $action_file );
        // algunas acciones incluyen un header Location, por lo que ya no continua la ejecución.
        // otras páginas solo ejecutan acciones y continuan con la ejecución normal de la página solicitada
    } else {
        // render 404  TODO: cambiar esta inclusión por un mensaje de error en el que diga que no se encuentra la acción solicitada
        // $render_page = ABSPATH . '/pages/' . $render_page . '.php';
        echo 'error 404 en acciones: ' . $action_file;
    }
}

$current_url = $_SERVER['REQUEST_URI'];
if (substr($current_url,-1) != '/'){
    $current_url .= '/';
}

require_once INC . 'builder.php'; // nos regresa la página que se debería mostrar

