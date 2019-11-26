<?

namespace App\Core;

class UserGroup extends Model
{

    public static function _(string $name)
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
