<?php
require 'printer/autoload.php';        
// use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;

// Conexión por Impresora compartida
// $nombre_impresora = "pos80c";
// $connector = new WindowsPrintConnector($nombre_impresora);
// Conexión por IP
$connector = new NetworkPrintConnector("192.168.100.3", 9001);
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
    //$printer->bitImage($logo);
    $printer->text("\n\n");
    // cabecera
    $printer->text("Intimus la terraza" . "\n");
    $printer->text("Cafe - Bar y Parrilla" . "\n");
    $printer->text(date("d-m-Y H:i:s") . "\n");
    
    // Información de cuenta
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    



    $printer->text("Orden No: " . $c['nombre'] . '. Cliente: General' . "\n");
    $printer->text('------------------------------------------------' . "\n");
    $printer->text('Cant. Concepto                      $      Total' . "\n");
    $printer->text('------------------------------------------------' . "\n");

    foreach($c['productos'] as $p ){
        $text = 
            fill($p['cantidad'], 6) . 
            fill($p['nombre'], 28) . 
            '$' . fill($p['precio'], 6, ' ', false) .
            '$' . fill($p['total'], 6, ' ', false);
        echo $text . '<br>';
        $printer->text($text . "\n");
    }
    $printer->text(fill('', 35) .'-------------' . "\n");
    $printer->text(fill('', 30) . 'TOTAL: $' .  fill($c['total'], 10, ' ', false) . "\n");
    // Termina cuenta

    // footer
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->text("\n");
    $printer->text("Es un placer Atenderlo\n");
    $printer->text("Este ticket no es un comprobante fiscal\n");
    
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