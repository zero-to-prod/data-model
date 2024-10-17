<?php

namespace Tests\Unit\DataModel\Recursion;

use Zerotoprod\DataModel\DataModel;

class Child
{
    use DataModel;

    public const id = 'id';
    public const name = 'name';

    public int $id;
    public string $name;
}