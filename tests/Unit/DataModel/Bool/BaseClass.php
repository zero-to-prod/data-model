<?php

namespace Tests\Unit\DataModel\Bool;

use Zerotoprod\DataModel\DataModel;

readonly class BaseClass
{
    use DataModel;

    public const bool = 'bool';
    public const Child = 'Child';

    public bool $bool;
    public Child $Child;
}