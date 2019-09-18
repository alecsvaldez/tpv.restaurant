<?php
require 'printer/autoload.php';        
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
// use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

// Conexión por Impresora compartida
$nombre_impresora = "XPCaja";
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
    $printer->text("Burger & Grill" . "\n");
    $printer->text("Restaurante Bar y Parrilla" . "\n");
    $printer->text(date("d-m-Y H:i:s") . "\n\n");
    
    // Información de cuenta
    $printer->setJustification(Printer::JUSTIFY_LEFT);

    $printer->text("Orden No: " . $c['nombre'] . '. Cliente: Burger & Grill' . "\n");
    $printer->text('------------------------------------------------' . "\n");
    $printer->text('Cant Concepto                     $        Total' . "\n");
    $printer->text('------------------------------------------------' . "\n");

    foreach($c['productos'] as $p ){
        $text = 
            fill($p['cantidad'], 4) . 
            fill($p['nombre'], 27) . 
            '$' . fill($p['precio'], 7, ' ', false) .
            ' $' . fill($p['total'], 7, ' ', false);
        $printer->text($text . "\n");
    }
    $printer->text(fill('', 31) .'-----------------' . "\n");
    if ($c['descuento']  > 0 ){
        $printer->text(fill('', 22) . '  Descuento:   $' .  fill($c['descuento'], 10, ' ', false) . "\n");
    }
    if ($c['iva_desglosado']){
        $printer->text(fill('', 22) . '   Subtotal:   $' .  fill($c['subtotal'], 10, ' ', false) . "\n");
        $printer->text(fill('', 22) . '        IVA:   $' .  fill($c['iva'], 10, ' ', false) . "\n");
    }
    $printer->text(fill('', 22) . '      TOTAL:   $' .  fill($c['total'], 10, ' ', false) . "\n");
    // Termina cuenta

    $m = array('meses', 'ene','feb','mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic');
    // footer
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->text("\n");
    $printer->text("Es un placer Atenderlo\n");
    $printer->text("Este ticket no es un comprobante fiscal\n");
    $printer->text("En caso de requerir factura, favor de\n");
    $fecha = date('t') . '-' . $m[date('n')] . '-' . date('Y');
    $printer->text("solicitarla al mesero antes del " . $fecha  . "\n" );
    $printer->text("\n\n\n");
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
    if (isset($abrir_caja) && $abrir_caja){
        $printer->pulse();
    }
    
} finally {
    /*
        Para imprimir realmente, tenemos que "cerrar"
        la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
    */
    $printer -> close();
}
