<?php

namespace Tests\Unit\DataModel\Bool;

use Zerotoprod\DataModel\DataModel;

readonly class Child
{
    use DataModel;

    public const bool = 'bool';
    public bool $bool;
}