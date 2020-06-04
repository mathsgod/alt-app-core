<?php

namespace App\Core;

class UserGroup extends Model
{
    public $usergroup_id;
    public $name;

    public static function _(string $name)
    {
        $ug = self::Query()
            ->where("name=:name or code=:code", ["name" => $name, "code" => $name])
            ->first();
        return $ug;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function removeUser(User $user)
    {
        foreach ($user->UserList() as $ul) {
            if ($ul->usergroup_id == $this->usergroup_id) {
                $ul->delete();
            }
        }
        return;
    }

    public function hasUser(User $user): bool
    {
        foreach ($user->UserList() as $ul) {
            if ($ul->usergroup_id == $this->usergroup_id) return true;
        }
        return false;
    }

    public function addUser(User $user)
    {
        //check if exists
        if ($ul = UserList::Query([
            "usergroup_id" => $this->usergroup_id,
            "user_id" => $user->user_id
        ])->first()) {
            return $ul;
        }

        $ul = new UserList();
        $ul->user_id = $user->user_id;
        $ul->usergroup_id = $this->usergroup_id;
        $ul->save();
        return $ul;
    }
}
