<?php
namespace TPV;
use TPV\Models\User;

defined('_PUBLIC_ACCESS') or die('Acceso denegado.');
allowed_request('POST') or die();

$data = array(
    'username' => $_POST['username'],
    'password' => $_POST['password']
);
$User = new User();
$login = $User->loginUser($data);

if ( $login !== false &&  $login['result'] == 'ok' ){
    $_SESSION['sesion_iniciada'] = 1;
    $_SESSION['site'] = SITE_NAME;
    $_SESSION['id'] = $login['id'];
    $_SESSION['username'] = $login['username'];
    // $_SESSION['email'] = $login['email'];
    
    // $Data->setLastLogin($login['id']);

    // TODO: usar $config['login_redirect']
    header('Location: ' . $site_url);
    exit;

} elseif ($login['result'] == 'error') {
    $message = array(
        'type' => 'error',
        'class' => 'danger',
        'message' => 'Verifica tu nombre de usuario o contraseña.',
        'submessage' => 'Credenciales incorrectas',
    );
} else {
    $error = $User->executeError();
    switch($error[0]){
        case 23000: $submessage = 'Error al consultar los usuarios'; break;
        default: $submessage = $error[2];
    }
    $message = array(
        'type' => 'error',
        'class' => 'danger',
        'message' => 'Ocurrió un error al intentar iniciar la sesión',
        'submessage' => $submessage,
    );
}
