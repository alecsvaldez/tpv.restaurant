<?php
defined('_PUBLIC_ACCESS') or die();

$table = 'tb_menus_productos';
$id_menu = (isset($_POST['id_menu']) && $_POST['id_menu'] > 0) ? $_POST['id_menu'] : 0;

if ($id_menu > 0){
    foreach($_POST['productos'] as $id_producto => $val){
        $data = array(
            'IdMenu' => $id_menu,
            'IdProducto' => $id_producto,
            'Precio' => $val['precio'],
            'Costo' => $val['costo']
            //'id' => $val['id_registro']
        );

        if ($val['id_registro'] > 0){
            $data['IdUsuarioModifica'] = $_SESSION['id'];
            $data['FechaModifica'] = date('Y-m-d H:i:s');
            $data['id'] = $db->updateById($table, $data, 'id', $val['id_registro']);
        } else {
            $data['IdUsuarioCrea'] = $_SESSION['id'];
            $data['FechaCrea'] = date('Y-m-d H:i:s');
            $data['id'] = $db->insert($table, $data);
        }
    }
    sessionMessage('success', 'Los productos han sido agreados al menú.');
    header('Location: /administracion/menus');
} else {
    sessionMessage('error', 'Información Incompleta', 'No se ha especificado un id de menú válido.');
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}
exit;