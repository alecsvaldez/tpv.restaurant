<?php
namespace TPV;
define('_PUBLIC_ACCESS',1);

if (!file_exists('config.php')){
    require 'install/defaults.php';
    require 'install/index.php';
}

require_once 'inc/init.php';
$render_page = renderPage();
include_once ABSPATH . 'html.php';

