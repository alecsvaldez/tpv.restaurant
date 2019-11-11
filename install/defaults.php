<?php
defined('_PUBLIC_ACCESS') or die();

$region = 'esm';
setlocale(LC_ALL, $region );

/** Define ABSPATH as this file's directory */
define( 'ROOTPATH', ( str_replace('/', DIRECTORY_SEPARATOR, $_SERVER['DOCUMENT_ROOT'] )) . DIRECTORY_SEPARATOR );
define( 'ABSPATH', dirname( dirname( __FILE__ )) . DIRECTORY_SEPARATOR );
define( 'APPDIR', str_replace(DIRECTORY_SEPARATOR, '/', str_replace(ROOTPATH, '', ABSPATH) ) );
define( 'INC', ABSPATH . 'inc' . DIRECTORY_SEPARATOR );
define( 'ENV', 'dev' );

define('SITE_NAME','TPV Restaurant');
define('DB_HOST','localhost');
define('DB_PORT',3306);
define('DB_USER','root');
define('DB_PASS','mysql');
define('DB_NAME','TPVRestaurant');

$site_url = $_SERVER['HTTP_HOST'] . '/' . APPDIR;
$site_url = 'http://' . $site_url;

$site_link = '';
$site_name = 'TPV Restaurant';
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
