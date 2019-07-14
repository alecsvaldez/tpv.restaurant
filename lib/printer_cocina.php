<?php
require 'printer/autoload.php';        
// use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

// Conexión por Impresora compartida
$nombre_impresora = "XP80CCaja";
$connector = new WindowsPrintConnector($nombre_impresora);
// Conexión por IP
// $connector = new NetworkPrintConnector("192.168.100.3", 9001);
$printer = new Printer($connector);

function fill($str, $len = 48,  $fill = ' ', $right = true){
    if (strlen($str) < $len){
        while(strlen($str) < $len){
            if ($right){
                $str .=  $fill;
            } else {
                $str =  $fill . $str;
            }
            if (strlen($str) > $len){
                break;
            }
        } 
    }
    return $str;
}
try {
    // ... Print 
    $logo = EscposImage::load( ROOTPATH . "assets/images/logo_ticket.jpg");
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->bitImage($logo);
    $printer->text("\n\n");
    // cabecera
    $printer->text("Intimus la terraza" . "\n");
    $printer->text(date("d-m-Y H:i:s") . "\n\n");
    
    // Información de cuenta
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    
    $printer->text("Orden No: " . $c['nombre'] . '.          Mesa: ' . $c['mesa'] . "\n");
    $printer->text('' . "\n\n");
    $printer->text('Cant. Producto                      ' . "\n");
    $printer->text('------------------------------------------------' . "\n");
    
    foreach($c['productos'] as $p ){
        if (isset($p['en_preparacion']) && $p['en_preparacion'] == 1) continue 1;

        $text = fill($p['cantidad'], 6) . 
        fill($p['nombre'], 40);
        $printer->text($text . "\n");
        if (!empty($p['comentarios'])){
            $printer->text($p['comentarios'] . "\n");
        }
        $printer->text('----------' . "\n");
    }
    // Termina orden

    // footer
    $printer->text('------------------------------------------------' . "\n");
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->text("\n");
    $printer->text("FIN DE LA COMANDA\n");
    $printer->text("\n\n");
    
    /*
        Cortamos el papel. Si nuestra impresora
        no tiene soporte para ello, no generará
        ningún error
    */
    $printer->cut();

    /*
        Por medio de la impresora mandamos un pulso.
        Esto es útil cuando la tenemos conectada
        por ejemplo a un cajón
    */
    //$printer->pulse();
} finally {
    /*
        Para imprimir realmente, tenemos que "cerrar"
        la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
    */
    $printer -> close();
}