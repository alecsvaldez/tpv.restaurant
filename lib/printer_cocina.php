<?php
require 'printer/autoload.php';        
// use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

// Conexión por Impresora compartida
$nombre_impresora = "XPCocina";
$connector = new WindowsPrintConnector($nombre_impresora);
// Conexión por IP
// $connector = new NetworkPrintConnector("192.168.100.3", 9001);
$printer = new Printer($connector);

$cat_barra = array(1,4,5,6,10,12,14,16,18,19);

$productos_cocina = array();
$productos_barra = array();
foreach($c['productos'] as $p ){
    if (isset($p['en_preparacion']) && $p['en_preparacion'] == 1) continue 1;
    if (in_array($p['id_categoria'], $cat_barra) ){
        $productos_barra[] = $p;
    } else {
        $productos_cocina[] = $p;
    }    
}

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
if (count($productos_cocina) > 0){

  try {
      echo ABSPATH . "assets/images/logo_ticket.jpg";
      // ... Print 
      $logo = EscposImage::load( ABSPATH . "assets/images/logo_ticket.jpg");
      $printer->setJustification(Printer::JUSTIFY_CENTER);
      $printer->bitImage($logo);
      $printer->text("\n\n");
      // cabecera
      $printer->text("Burger & Grill" . "\n");
      $printer->text(date("d-m-Y H:i:s") . "\n\n");
      
      // Información de cuenta
      $printer->text("COCINA   ----  Orden No: " . $c['nombre'] . '.  Mesa: ' . $c['mesa'] . "\n");
      $printer->setJustification(Printer::JUSTIFY_LEFT);
      $printer->text('' . "\n\n");
      $printer->text('Cant. Producto                      ' . "\n");
      $printer->text('------------------------------------------------' . "\n");
      
      foreach($c['productos'] as $p ){
          if (isset($p['en_preparacion']) && $p['en_preparacion'] == 1) continue 1;
          if (in_array($p['id_categoria'], $cat_barra) ) continue 1;

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
      $printer->close();
  } finally {
      /*
          Para imprimir realmente, tenemos que "cerrar"
          la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
      */
      // $printer -> close();
  }
}
if (count($productos_barra) > 0){

  // Conexión por Impresora compartida  --- BARRA
  $nombre_impresora = "XPBarra";
  $connector_c = new WindowsPrintConnector($nombre_impresora);
  $printer_c = new Printer($connector_c);
  try {
      // ... Print 
      $logo = EscposImage::load( ABSPATH . "assets/images/logo_ticket.jpg");
      $printer_c->setJustification(Printer::JUSTIFY_CENTER);
      $printer_c->bitImage($logo);
      $printer_c->text("\n\n");
      // cabecera
      $printer_c->text("Burger & Grill" . "\n");
      $printer_c->text(date("d-m-Y H:i:s") . "\n\n");
      
      // Información de cuenta
      $printer_c->text("BARRA    ---- Orden No: " . $c['nombre'] . '.   Mesa: ' . $c['mesa'] . "\n");
      
      $printer_c->setJustification(Printer::JUSTIFY_LEFT);
      
      $printer_c->text('' . "\n\n");
      $printer_c->text('Cant. Producto                      ' . "\n");
      $printer_c->text('------------------------------------------------' . "\n");
      
      foreach($c['productos'] as $p ){
          if (isset($p['en_preparacion']) && $p['en_preparacion'] == 1) continue 1;
          if (!in_array($p['id_categoria'], $cat_barra) ) continue 1;
          $text = fill($p['cantidad'], 6) . 
          fill($p['nombre'], 40);
          $printer_c->text($text . "\n");
          if (!empty($p['comentarios'])){
              $printer_c->text($p['comentarios'] . "\n");
          }
          $printer_c->text('----------' . "\n");
      }
      // Termina orden

      // footer
      $printer_c->text('------------------------------------------------' . "\n");
      $printer_c->setJustification(Printer::JUSTIFY_CENTER);
      $printer_c->text("\n");
      $printer_c->text("FIN DE LA COMANDA\n");
      $printer_c->text("\n\n");

      $printer_c->cut();
      
      $printer_c->close();
      
  } finally {
      // $printer_c->close();
  }
}
