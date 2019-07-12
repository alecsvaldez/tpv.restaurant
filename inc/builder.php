<?php
namespace TPV;
defined('_PUBLIC_ACCESS') or die();

function renderPage(){
    $page_path = ABSPATH . 'pages';
    $seccion = '/';
    $subseccion = '';
    $accion = '';
    $page = 'login';
    
    if (is_logged()){
        // Páginas con comportamiento especial OJO que estas se llaman acTion, y no accion como los otros procesos
        if (!empty($_REQUEST['action'])){
            require ABSPATH . 'actions/' . $_REQUEST['action'] . '.php';
            return false;
        }

        $page = 'home';
        // Opcional puede venir definida una sección, que representa un subdirectorio
        if (!empty($_REQUEST['seccion'])){
            $seccion .= strtolower(str_replace('-','_',$_GET['seccion'])) . '/';
        }
        if (!empty($_REQUEST['subseccion'])){
            $subseccion .= strtolower(str_replace('-','_',$_GET['subseccion'])) . '/';
            // $subseccion = '/' . strtolower(str_replace('-','_',$_GET['subseccion']));
            // $subseccion_index = '/' . strtolower(str_replace('-','_',$_GET['subseccion'])) . '/index';
        }      
        if (!empty($_GET['accion'])){
            $accion .= strtolower(str_replace('-','_',$_GET['accion'])) . '/';
        }      
        // page es la página que se va a mostrar en el body
        if (!empty($_REQUEST['page'])){
            $page = strtolower(str_replace('-','_',$_GET['page']));
            $page_index = strtolower(str_replace('-','_',$_GET['page'])) . '/index';
        }      

    }

    $file = $page_path . $seccion . $subseccion . $accion . $page . '.php';
    // echo 'trying to locate: <b>' . $file . '</b>';

    // primera opcion  es buscar el archivo que se llama "tal cual"
    if (!file_exists($file)){
        // segunda opcion es buscar la carpeta con el index
        $file = $page_path . $seccion . $subseccion . $accion . $page_index . '.php';
        if (!file_exists($file)){
            $seccion = '/';
            $subseccion = '';
            $accion = '';
            $page = '404';
            $file = $page_path . $seccion . $accion . $page . '.php';
        }
    }
    return  $file;
}
