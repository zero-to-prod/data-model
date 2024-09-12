<?php

namespace Tests\Unit\DataModel\Int;

use Zerotoprod\DataModel\DataModel;

readonly class BaseClass
{
    use DataModel;

    public const int = 'int';
    public const Child = 'Child';
    public int $int;
    public Child $Child;
}