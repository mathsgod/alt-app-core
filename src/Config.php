<?php

namespace App\Core;

class Config extends Model
{
    public function __toString()
    {
        return $this->value;
    }
}
