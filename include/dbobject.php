<?php 


class DatabaseObject {
    
    // ------------------------------ QUERIES -------------------------------- \\
    // main method for queries (create instance from query results)
    public static function find_by_query($q){
        global $c;
        $object_array = array();
        
        $result_set = $c->query($q);
        
        while($row = $c->fetch_array($result_set)){
            $object_array[] = self::create_instance($row);
        }
        return $object_array;
    }
 
    public static function find_by_id($id){
        global $c;
        //$safe_id = $c->safe_string($id);
        
        $q  = "SELECT * FROM " . static::$table_name . " ";
        $q .= "WHERE id = '$id' LIMIT 1"; 
        $result = self::find_by_query($q);

        return empty($result) ? null : array_shift($result);
    }
    
    public static function find_all($q=""){
        return $result = self::find_by_query("SELECT * FROM " . static::$table_name . " " . $q);
    }
    
    public static function count_all(){
        global $c;
        
        $q = "SELECT COUNT(*) FROM " . static::$table_name;
        $r = $c->query($q);
        $result_set = $c->fetch_array($r);
        return array_shift($result_set);
    }
    
    protected static function pagination_query($per_page, $offset, $order){
        $q  = "SELECT * FROM " . static::$table_name . " ";
        $q .= "ORDER BY $order ASC ";
        $q .= "LIMIT $per_page ";
        $q .= "OFFSET " . $offset;
        $class = get_called_class();
        return $class::find_by_query($q);
    }
    
     // ----------------END--------------- queries ------------END------------- \\

    
    
    
    // ------------------------------- CREATE INSTANCE ---------------------- \\
    private static function create_instance($record){
        $called_class = get_called_class();
        $object = new $called_class;
        
        foreach($record as $attribute => $value){
            if($object->has_attribute($attribute)){
                $object->$attribute = $value;
            }
        }
        return $object;
    }

    // check if called class has attributes
    private function has_attribute($attribute){
        $object_vars = get_object_vars($this);
        return array_key_exists($attribute, $object_vars);
    }
    // ----------------END--------------- create instance ------------END------------- \\
    
    
    
    /************************** CRUD ****************************/
    // find class attributes
    private function attributes(){
        $attr = array();

        foreach(static::$db_fields as $field){
            $attr[$field] = $this->$field;
        }
        
        return $attr;
    }
    
    // escape attributes
    private function safe_attributes(){
        $safe = array();
        global $c;
        
        foreach($this->attributes() as $key => $value){
            $safe[$key] = $c->safe_string($value);
        }
        return $safe;
        
    }
    
    public function create(){
        //$escaped = $this->safe_attributes();
        global $c;
        //var_dump("create");
        $q  = "INSERT INTO " . static::$table_name . " ( ";
        $q .= join(", ", array_keys($this->attributes()));
        $q .= " ) VALUES ( '";
        $q .= join("', '", array_values($this->safe_attributes()));
        $q .= "' )";
        $result = $c->query($q);
        if($result && $c->affected_rows() >= 1){
            $this->id = $c->insert_id();
            return true;
        } else {
            return false;
        }
    }
    
    public function update(){
        global $c;
        //var_dump("update");
        $pairs = array();
        foreach($this->safe_attributes() as $attr => $value){
            $pairs[] = $attr . " = " . "'$value'" . " ";
        }
        
        $q  = "UPDATE " . static::$table_name . " SET ";
        $q .= join(", ", $pairs);
        $q .= " WHERE id = " . $this->id . " LIMIT 1";
        $r  = $c->query($q);
        if($r && $c->affected_rows() >= 1){
            return true;
        } else {
            return false;
        }
    }
    
    public function delete(){
        global $c;
        
        $q  = "DELETE FROM " . static::$table_name . " ";
        $q .= "WHERE id = " . $c->safe_string($this->id) . " LIMIT 1";
        $r  = $c->query($q);
        if($r && $c->affected_rows() == 1){
            return true;
        } else {
            return false;
        }
    }
    
    public function save(){
        return isset($this->id) ? $this->update() : $this->create();
    }
    
}






?>