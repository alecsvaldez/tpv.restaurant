<?php
defined('_PUBLIC_ACCESS') or die();
class db {
    protected static $db;
    static $stmt;
    static $sql = '';
    static $echo_error = true;
    static $show_sql = false;
    public function __construct( ) {
        self::$db = new PDO( 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS );
        self::$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        self::$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
    public function __destruct(){
        //self::$db = null;
    }
    
    protected static function showSql($data = null, $replace = true){
        echo '<pre>';
        if ($data != null){
            $sql = self::$sql;
            if ($replace){
                foreach($data as $param => $value){
                    $sql = str_replace(':' . $param, $value, $sql);
                }
            }
            print_r($sql);
        } else {
            print_r(self::$sql);
        }
        echo '</pre>';
    }
    public static function enableShowSql(){
        self::$show_sql = true;
    }
    public static function query($sql, $data = null){
        self::$sql = $sql;
        self::$stmt = self::$db->prepare($sql);
        if (self::$stmt === false){
            if (self::$echo_error){
                echo '<b class="text-danger">Error al ejecutar la consulta:</b> ' . self::$db->errorInfo()[2];
            }
            return false;
        }
        if ($data != null){
            // Si hay data, haz el bind
            foreach($data as $param => $value){
                self::bind($param,$value);
            }
            return true;
        } else {
            return true;
        }
    }

    private static function bind($param, $value, $type = null){
        if (is_null($type)) {
			switch (true) {
    			case is_int($value):
    				$type = PDO::PARAM_INT;
    				break;
			    case is_bool($value):
    				$type = PDO::PARAM_BOOL;
    				break;
    			case is_null($value):
    				$type = PDO::PARAM_NULL;
    				break;
				default:
    				$type = PDO::PARAM_STR;
			}
        }
        self::$stmt->bindValue($param, $value);
    }

    protected static function execute(){
        self::$stmt->execute();
    }

    public static function executeError(){
        $error = self::$db->errorInfo();
        $message = '';
        switch($error[0]){
            case 23000: $message = 'Ya existe un registro con información similar. Intenta registrar un dato diferente.'; break;
            case '42S02': $message = 'La tabla "' . $table . '" no existe.'; break;
            default: $message = $error[2];
        }
        $error['db_message'] = $message;
        return $error;
    }
    protected static function rowCount(){
        return self::$stmt->rowCount();
    }

    public static function get($sql, $data = null){
        if (self::query($sql)){
            if ($data != null){
                foreach($data as $param => $value){
                    self::bind($param,$value);
                }
            }
            self::execute();
            return self::$stmt->fetchAll();
        } else {
            return false;
        }
    }
    // public static function first($sql, $data = null){
    //     $result = self::get($sql, $data);
        
    // }
    public static function select($table, $columns , $conditions, $limit = 100){
        $sql = 'SELECT ';
        if (empty($columns)){
            $sql .= ' * ';
        } else {
            foreach($columns as $alias => $col){
                $sql .= ' 
                ' . $col . ' ' . (!is_numeric($alias) ? 'AS ' . $alias : '') . ',';
            }
        }
        $sql = trim($sql,',');
        $sql .= '
        FROM ' . $table;
        if (!empty($conditions)){
            $sql .= '
        WHERE ' . implode(' AND ',$conditions);

        }
        if (is_numeric($limit) && $limit > 0){
            $sql .= ' 
        LIMIT ' . $limit;
        }
        //print_array($sql);
        self::$sql = $sql;
        if (self::$show_sql){
            self::showSql($data);
            return false;
        }
        if (self::query($sql)){
            // foreach($data as $param => $value){
            //     self::bind($param,$value);
            // }
            self::execute();
            // if (self::rowCount() > 0)
            return self::$stmt->fetchAll();
            
        }
        return false;

    }
    public static function find($table, $columns , $conditions){
        $data = self::select($table, $columns, $conditions);
        if (count($data) > 0 ){
            return $data[0];
        } else {
            return false;
        }
    }
    public static function first($sql, $data = null){
        if (self::query($sql)){
            if ($data){
                foreach($data as $param => $value){
                    self::bind($param,$value);
                }
            }
            self::execute();
            if (self::rowCount() > 0)
                return self::$stmt->fetch();
        }
        return false;
    }

    public static function insert($table, $data){
        $sql = "INSERT INTO " . $table . " ( ";
        foreach ($data as $col => $val){
            $sql .= '
            ' . $col . ',';
        }
        $sql = trim($sql,',');
        $sql .= "
        ) VALUES ( ";
        foreach ($data as $col => $val){
            $sql .= '
            :' . $col . ',';
        }
        $sql = trim($sql,',');
        $sql .= "
        )";
        self::$sql = $sql;
        if (self::$show_sql){
            self::showSql($data);
            return false;
        }
        if ( self::query($sql) ) {
            foreach ($data as $col => $val){
                self::bind(':' . $col, $val);
            }
            self::execute();
            return self::$db->lastInsertId();
        } else {
            return false;
        }
    }
    // TODO
    public static function update($table, $data, $pk = null, $id = null){}

    public static function updateById($table, $data, $pk = null, $id = null){
        if ($pk != null && $id != null && $id > 0){
            $sql = "UPDATE " . $table . " SET ";
            foreach ($data as $col => $val){
                if ($col == $pk) continue;
                $sql .= '
                ' . $col . ' = :' . $col . ',';
            }
            $sql = trim($sql,',');
            $sql .= "
            WHERE " . $pk . " = :" . $pk;
            self::$sql = $sql;
            if (self::$show_sql){
                self::showSql($data);
                return false;
            }
    
            if ( self::query($sql) ) {
                foreach ($data as $col => $val){
                    if ($col == $pk) continue;
                    self::bind(':' . $col, $val);
                }
                self::bind(':' . $pk, $id);
                self::execute();
                return $id;
            } else {
                return false;
            }
        }
    }

    public static function delete($table,$pk,$id){
        if ($pk != null && $id != null && $id > 0){
            $sql = "DELETE FROM " . $table . "
            WHERE " . $pk . " = :" . $pk;

            if ( self::query($sql) ) {
                self::bind(':' . $pk, $id, PDO::PARAM_STR);
                self::execute();
                return $id;
            } else {
                return false;
            }
        }
    }

}
