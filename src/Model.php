<?php

namespace App\Core;

class Model extends \R\ORM\Model
{
    public static $_db;
    public static function __db()
    {
        return self::$_db;
    }

    
    public function save()
    {
        $key = $this->_key();

        if (!$this->$key) { //insert
            if (property_exists($this, "created_time")) {
                $this->created_time = date("Y-m-d H:i:s");
            }
        } else {
            if (property_exists($this, "updated_time")) {
                $this->updated_time = date("Y-m-d H:i:s");
            }
        }

        return parent::save();
    }
}
