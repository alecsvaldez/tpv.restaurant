<?php
namespace TPV\Models;
use db ;
//defined('_PUBLIC_ACCESS') or die('Acceso denegado.');
class User {

    public function loginUser($data){
        $sql = "SELECT
            id
            , Usuario as usuario
        FROM tb_usuarios
        WHERE Activo = 1
            AND Usuario = :username
            AND Contrasena = :password ";

        $user = db::first($sql,$data);
        if ($user !== false){
        	if (count($user) > 0){
        		return array(
                    'result' => 'ok',
                    'id' => $user['id'],
                    'username' => $user['usuario'],
                    // 'email' => $user['email'],
                );
            }
        }
        return array('result' => 'error');
    }

    public function setLastLogin($id_user) {
        $sql = "UPDATE tb_usuarios SET
            UltimoIngreso = NOW()
        WHERE id = :id_user";
        if ( db::query($sql, array('id_user' => $id_user) ) ) {
        	// db::show_sql(array('id_user' => $id_user));exit;
            db::execute();
            return db::rowCount();
        } else {
            return false;
        }
    }
    
    public function getUserData($id_usuario){
        $sql = "SELECT
            u.id
            , u.Usuario as usuario
            , r.Rol AS rol
            , u.Pin
        FROM tb_usuarios u
            INNER JOIN tb_roles r ON r.id = u.IdRol
        WHERE u.id = :id_usuario ";
        $usuario = db::first($sql, array('id_usuario' => $id_usuario));
        if ($usuario !== false){
        	if (count($usuario) >= 1){
        		return $usuario;
            }
        }
        return array('result' => 'error');
    }

    public function getUserByPin($data){
        $sql = "SELECT
            id
            , Usuario AS usuario
            , Nombre AS nombre
            , Apellidos AS apellidos
        FROM tb_usuarios
        WHERE Activo = 1
            AND Pin = :pin";

        $user = db::first($sql,$data);
        if ($user !== false){
        	if (count($user) > 0){
        		return array(
                    'result' => 'ok',
                    'id' => $user['id'],
                    'username' => $user['usuario'],
                    'nombre' => strtok($user['nombre'], " ") . ' ' . strtok($user['apellidos'], " ")
                );
            }
        }
        return array('result' => 'error');
    }    
}
