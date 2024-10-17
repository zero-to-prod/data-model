<?php

namespace Tests\Unit\DataModel\Float;

use Zerotoprod\DataModel\DataModel;

class BaseClass
{
    use DataModel;

    public const float = 'float';
    public const Child = 'Child';
    public float $float;
    public Child $Child;
}