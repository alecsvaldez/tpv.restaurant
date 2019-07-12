<?php

defined('_PUBLIC_ACCESS') or die();

function __autoload($class_name) {
    $file = str_replace("\\", DIRECTORY_SEPARATOR, $class_name); 
	require_once str_replace('\\', DIRECTORY_SEPARATOR, $_SERVER['DOCUMENT_ROOT']) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . $file . ".php";
}
function allowed_request($request_type){
    if ($_SERVER['REQUEST_METHOD'] !== $request_type){
        header('Location: ./');
        return false;
    } else {
        return true;
    }
}

function sanitize($string){
    return filter_var($string, FILTER_SANITIZE_ENCODED);
}
function clean($string) {
    $string = trim($string);
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}
function print_array($array){
    echo '<pre  style="text-align: left">';
    print_r($array);
    echo '</pre>';
}

function showSessionMessage(){
    if (!empty($_SESSION['db_message'])){
        $message = $_SESSION['db_message'];    
        unset($_SESSION['db_message']);
        ?>
    <section class="content-header">
        <div class="alert alert-<?php echo $message['class'] ?> alert-dismissible"> 
            <button type="button" class="close" data-dismiss="alert">×</button>
            <p>
                <i class="icon <?php echo $message['icon'] ?>"></i>
                <?php echo $message['message'] ?>
                <?php
                if ($message['type'] == 'error'){
                    ?>
                <br><small><?php echo $message['submessage'] ?></small>
                    <?php
                }
                ?>
            </p>
        </div>
    </section>    
        <?php
    }
}

function sessionMessage($type, $message = false, $submessage = false){
    switch($type){
        case 'success': $class = $type;    $icon = 'fa fa-check'; $default_message = '¡Éxito!'; break;
        case 'error':   $class = 'danger'; $icon = 'fa fa-ban';   $default_message = '¡Error!'; break;
        case 'warning': $class = $type;    $icon = 'fa fa-warning';$default_message = '¡Cuidado!'; break;
        case 'info':
        default:        $class = $type;    $icon = 'fa fa-info';  $default_message = '¡Aviso!'; break;
    }
    $_SESSION['db_message'] = array(
        'type' => $type,
        'class' => $class,
        'icon'  => $icon,
        'message' => ($message ? $message : $default_message),
        'submessage' => ($submessage ? $submessage : $default_message),
    );
}

function generateRandomString($length = 10) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}

function idToString($id, $array, $not_found = ''){
    if (isset($array[$id])){
        return $array[$id];
    }
    return $not_found;
}

function getMoney($money, $simbolo = true,$decimales = 2){
     if (!is_numeric($money)){
        return $money;
    }
    $money = (strlen($money) == 0) ? 0 : $money;
    if(!$decimales){
      $decimales = 0;
    }
    $negativo = ($money < 0);
    $money = abs($money);
    if ($decimales == 0){
        $money = round($money,0);
    }

    $locale = localeconv();

    $currency = number_format($money, 2, $locale['decimal_point'], $locale['thousands_sep']);

    if ($simbolo === FALSE ){
        $currency = $currency;
    } elseif ($simbolo === true){
        $currency = $locale['currency_symbol'] . ' ' . $currency;
    } else {
        $currency = $simbolo . ' ' . $currency;
    }


    return (($negativo) ? '-' : '') . $currency;
}
function dateToString($date){
    if ($date != '0000-00-00' && !empty($date))
        return date("d M Y", strtotime($date));
    
    return '';
}
function humanFileSize($bytes, $decimals = 2, $si = true) {
    $pow = 1000;
    if (!$si) $pow = 1024; // IEC standard
    $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow($pow, $factor)) . @$size[$factor];
}