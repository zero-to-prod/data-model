<?php

namespace Tests\Unit\DataModel\Null;

use Zerotoprod\DataModel\DataModel;

class Child
{
    use DataModel;

    public const bool = 'bool';
    public bool $bool;
}