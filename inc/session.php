<?php
namespace TPV;
use TPV\Models\User;

defined('_PUBLIC_ACCESS') or die('Acceso denegado.');


if (!isset($_SESSION)){
    session_start();
}

if (is_logged()) {
    $user = (new User())->getUserData($_SESSION['id']);
} else {
    
}

function is_logged(){
    return (isset($_SESSION['sesion_iniciada']) && $_SESSION['sesion_iniciada'] == 1 && $_SESSION['site'] == SITE_NAME);
}
