<?php

namespace Tests\Unit\Examples\Usage;

use Tests\Unit\Examples\ExtendsTrait\DataModel;

class User
{
    use DataModel;

    public string $name;
    public int $age;
}