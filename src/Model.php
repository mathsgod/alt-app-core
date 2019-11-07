<?

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

    public function bind($rs)
    {
        foreach (get_object_vars($this) as $key => $val) {
            if (is_object($rs)) {
                if (isset($rs->$key)) {
                    if ($key[0] != "_") {
                        if (is_array($rs->$key)) {
                            $this->$key = implode(",", $rs->$key);
                        } else {
                            $this->$key = $rs->$key;
                        }
                    }
                }
            } else {
                if (array_key_exists($key, $rs)) {
                    if ($key[0] != "_") {
                        if (is_array($rs[$key])) {
                            $this->$key = implode(",", array_filter($rs[$key], function ($o) {
                                return $o !== "";
                            }));
                        } else {
                            $this->$key = $rs[$key];
                        }
                    }
                }
            }
        }
        return $this;
    }
}
