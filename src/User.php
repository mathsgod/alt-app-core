<?php

namespace App\Core;

use Exception;

class User extends Model
{
    const STATUS = ["Active", "Inactive"];

    public static function _(string $username)
    {
        return self::Query(["username" => $username])->first();
    }


    public function setPassword(string $password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->save();
    }

    public static function Login(string $username, string $password, $code = null)
    {
        $user = self::Query([
            "username" => $username,
            "status" => 0
        ])->first();

        if (!$user) {
            throw new Exception("user not found");
        }

        //check password
        if (!$user->verifyPassword($password)) {
            throw new Exception("password error");
        }

        if ($user->expiry_date && strtotime($user->expiry_date) < time()) {
            throw new Exception("user expired");
        }

        if ($user->UserList->count() == 0) {
            throw new Exception("no any user group");
        }

        return $user;
    }

    public function verifyPassword(string $password): bool
    {
        if ((substr($this->password, 0, 2) == "$6" || substr($this->password, 0, 2) == "$5")) {
            return self::Encrypt($password, $this->password) == $this->password;
        }

        return password_verify($password, $this->password);
    }

    private static $_is = [];
    public function is($name): bool
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

    public function isAdmin(): bool
    {
        return $this->is("Administrators");
    }

    public function isPowerUser(): bool
    {
        return $this->is("Power Users");
    }

    public function isUser(): bool
    {
        return $this->is("Users");
    }

    public function isGuest(): bool
    {
        return $this->is("Guests");
    }

    //for old version
    private static function Encrypt(string $str, $salt = null)
    {
        if ($salt == null) { //hash
            return password_hash($str, PASSWORD_DEFAULT);
        }

        $pass = "";
        $md5 = md5($str);
        eval(base64_decode("JHBhc3MgPSBtZDUoc3Vic3RyKHN1YnN0cigkbWQ1LC0xNiksLTgpLnN1YnN0cihzdWJzdHIoJG1kNSwtMTYpLDAsLTgpLnN1YnN0cihzdWJzdHIoJG1kNSwwLC0xNiksLTgpLnN1YnN0cihzdWJzdHIoJG1kNSwwLC0xNiksMCwtOCkpOw=="));
        if (is_null($salt)) {
            $rounds = rand(5000, 9999);
            if (CRYPT_SHA512 == 1) {
                $pass = crypt($pass, '$6$rounds=' . $rounds . '$' . md5(uniqid()) . '$');
            } elseif (CRYPT_SHA256 == 1) {
                $pass = crypt($pass, '$5$rounds=' . $rounds . '$' . md5(uniqid()) . '$');
            } else {
                $pass = crypt($pass);
            }
            return $pass;
        } else {
            return crypt($pass, $salt);
        }
    }
}
