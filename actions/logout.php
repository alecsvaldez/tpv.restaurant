<?php
defined('_PUBLIC_ACCESS') or die('Acceso denegado.');
allowed_request('GET') or die();

session_destroy();
header('Location: ./');
exit;