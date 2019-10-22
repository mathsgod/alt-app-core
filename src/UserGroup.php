<?

namespace App\Core;

use Cache\Adapter\PHPArray\ArrayCachePool;

class UserGroup extends Model
{

    public static $Cache = null;

    public static function _($name)
    {
        $ug = UserGroup::Query()
            ->where("name=:name or code=:code", ["name" => $name, "code" => $name])
            ->first();
        return $ug;
    }
    public function __toString()
    {
        return $this->name;
    }
}

UserGroup::$Cache = new ArrayCachePool();
