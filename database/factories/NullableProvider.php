<?php

namespace Database\Factories;

use Faker\Provider\Base;

class NullableProvider extends Base
{
    public function nullable($value = null)
    {
        return $this->generator->boolean(2) ? $value : null;
    }
}