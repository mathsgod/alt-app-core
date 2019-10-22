<?

namespace App\Core;

use Exception;


class User extends Model
{
    const STATUS = ["Active", "Inactive"];

    public static function _($username)
    {
        return self::Query(["username" => $username])->first();
    }

    public static function Login($username, $password, $code = null)
    {
        $user = self::Query([
            "username" => $username,
            "status" => 0
        ])->first();

        if (!$user) {
            throw new Exception("user not found");
        }

        //check password
        if (!password_verify($password, $user->password)) {
            throw new Exception("password error");
        }

        if ($user->expiry_date && strtotime($user->expiry_date) < time()) {
            throw new Exception("user expired");
        }

        return $user;
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    private static $_is = [];
    public function is($name)
    {
        if ($name instanceof UserGroup) {
            $group = $name;
        } else {
            $group = UserGroup::_($name);
            if (!$group) {
                return false;
            }
        }

        if (isset(self::$_is[$this->user_id])) {
            return in_array($group->usergroup_id, self::$_is[$this->user_id]);
        }

        self::$_is[$this->user_id] = [];
        foreach ($this->UserList as $ul) {
            self::$_is[$this->user_id][] = $ul->usergroup_id;
        }
        //self::$_app->log("is data", self::$_is);
        return in_array($group->usergroup_id, self::$_is[$this->user_id]);
    }

    public function UserGroup()
    {
        return UserGroup::Query()->where("usergroup_id in (select usergroup_id from UserList where user_id=:user_id)", ["user_id" => $this->user_id]);
    }

    public function __tostring()
    {
        return $this->first_name . " " . $this->last_name;
    }

    public function isAdmin()
    {
        return $this->is("Administrators");
    }

    public function isPowerUser()
    {
        return $this->is("Power Users");
    }

    public function isUser()
    {
        return $this->is("Users");
    }

    public function isGuest()
    {
        return $this->is("Guests");
    }
}
