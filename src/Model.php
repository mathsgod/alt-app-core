<?

namespace App\Core;

class Model extends \R\ORM\Model
{
    public static $_db;
    public static function __db()
    {
        return self::$_db;
    }
}
