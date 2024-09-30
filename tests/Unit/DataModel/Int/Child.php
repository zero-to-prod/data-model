<?php

namespace Tests\Unit\DataModel\Int;

use Zerotoprod\DataModel\DataModel;

readonly class Child
{
    use DataModel;

    public const int = 'int';
    public int $int;
}