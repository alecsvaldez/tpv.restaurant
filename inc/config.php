<?php
namespace TPV;
defined('_PUBLIC_ACCESS') or die();

$region = 'esm';
setlocale(LC_ALL, $region );
// setlocale(LC_MONETARY, 'en_US');
// setlocale(LC_NUMERIC, $region);

/** Define ABSPATH as this file's directory */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname(basename( __FILE__ )) . '/' );
}
if ( ! defined( 'INC' ) ) {
	define( 'INC', dirname( __FILE__ ) . '/' );
}
if ( ! defined( 'ENV' ) ) {
	define( 'ENV', 'dev' );
}
if ( ! defined( 'ROOTPATH' ) ) {
	define( 'ROOTPATH', ( ( $_SERVER['DOCUMENT_ROOT'] )) . '/' );
}

define('SITE_NAME','TPV_TERRAZA');

define('DB_HOST','localhost');
define('DB_PORT',3306);
define('DB_USER','root');
define('DB_PASS','mysql');
define('DB_NAME','TerrazaBar');

$site_domain = 'tpv.local';

if ($site_domain == $_SERVER['HTTP_HOST']){
    $site_url = $site_domain . '/';
} else {
    $site_url = $_SERVER['HTTP_HOST'] . '/' . basename(dirname(__FILE__,2)) . '/';
}
$site_url = 'http://' . $site_url;
$site_link = '';
$site_name = 'Intimus | La Teraza';
$site_slogan = '';
$site_description = '';
$site_author = '@alecsvaldez';

$site_assets = $site_url . 'assets/';
$site_images = $site_assets . 'images/';
$site_css = $site_assets . 'css/';
$site_js = $site_assets . 'js/';
$site_skins = $site_url . 'skins/';
$site_scripts  = $site_url . 'scripts/';

$upload_dir = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR  . "uploads" . DIRECTORY_SEPARATOR ;

$config = array(
    'enable_login' => true,
    'enable_register' => false,
    'enable_register_autologin' => false,
    'register_redirect' => 'login',
    'login_redirect' => 'home',
    'allow_recovery_password' => false,
);

/**
 * Roles disponibles
 * - ADMIN
 * - BOX
 * - MESAS
 * - ORDER
 * - RECADMIN
 * - USER
 */

$menu_top = array(
    'pos' => array(
        'url'   => $site_url . 'tpv',
        'label' => 'TPV',
        'icon'  => 'fa fa-cutlery',
        'permissions' => array(
            'ADMIN','MESAS'
        )
    ),
    'kitchen' => array(
        'url'   => $site_url . 'cocina',
        'label' => 'Cocina',
        'icon'  => 'fa fa-fire',
        'permissions' => array(
            'ADMIN','COCINA'
        )
    ),    
    'bar' => array(
        'url'   => $site_url . 'bar',
        'label' => 'Barra',
        'icon'  => 'fa fa-glass',
        'permissions' => array(
            'ADMIN','BARRA'
        )
    ),    
    'purchases' => array(
        'url'   => $site_url . 'compras',
        'label' => 'Compras',
        'icon'  => 'fa fa-truck',
        'permissions' => array(
            'ADMIN','RECADMIN','STOCK'
        )
    ),
    // 'summary' => array(
    //     'url'   => $site_url . 'summary',
    //     'label' => 'Reportes',
    //     'icon'  => 'fa fa-list',
    //     'permissions' => array(
    //         'ADMIN','USER'
    //     )
    // ),
    // 'alert-list' => array(
    //     'url'   => $site_url . 'alert-list',
    //     'label' => 'Pendientes',
    //     'icon'  => 'fa fa-hdd-o',
    //     'permissions' => array(
    //         'ADMIN','RECADMIN','STOCK'
    //     )
    // )
);