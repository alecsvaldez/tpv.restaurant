<?php
defined('_PUBLIC_ACCESS') or die();

// $db->show_sql = true; // prevent to wun query
$table = 'tb_productos';
$id = (isset($_POST['id']) && $_POST['id'] > 0) ? $_POST['id'] : 0;

//upload control
$upload_dir = ABSPATH . '/uploads/productos/';
$upload_temp = ABSPATH . '/uploads/productos/_temp/';
if (!file_exists($upload_temp)){
    mkdir($upload_dir,077,true);
    mkdir($upload_temp,077,true);
}
$temp_file = $upload_temp . basename($_FILES["foto"]["name"]);
$file_ext = strtolower(pathinfo($temp_file,PATHINFO_EXTENSION));

// Translate $_POST to Table Columns in $data
$data = array(
    'Producto' => $_POST['nombre'],
    'Descripcion' => $_POST['descripcion'],
    'IdCategoria' => $_POST['id_categoria'],
    'CostoBase' => $_POST['costo'],
    'PrecioBase' => $_POST['precio'],
    'IdTipoProducto' => isset($_POST['id_tipo_producto']) ? $_POST['id_tipo_producto'] : 0,
);

if ($id > 0){
    $data['IdUsuarioModifica'] = $_SESSION['id'];
    $data['FechaModifica'] = date('Y-m-d H:i:s');
    $id = $db->updateById($table, $data, 'id', $id);
} else {
    $data['IdUsuarioCrea'] = $_SESSION['id'];
    $data['FechaCrea'] = date('Y-m-d H:i:s');
    $id = $db->insert($table, $data);
}

if ($id !== false){

    //Copiamos la imagen con el nombre correcto, que es el id
    $target_file = $upload_dir . $id . '.' . $file_ext;
    if ($file_ext == 'png'){
        $image = imagecreatefrompng($target_file);
        $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
        imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
        imagealphablending($bg, TRUE);
        imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
        imagedestroy($image);
        $quality = 70; // 0 = worst / smaller file, 100 = better / bigger file 
        imagejpeg($bg, $filePath . ".jpg", $quality);
        imagedestroy($bg);
    }
    $target_file = $upload_dir . $id . '.jpg';
    move_uploaded_file($_FILES["foto"]["tmp_name"],  $target_file);

    sessionMessage('success', 'Los datos se han guardado.');
    header('Location: ' . $site_url . 'administracion/productos');
} else {
    $error = $db->executeError();
    sessionMessage('error', $error['db_message'], 'Ocurrió un error al registrar la información');
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}
exit;