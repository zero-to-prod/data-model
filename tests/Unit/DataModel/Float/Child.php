<?php

namespace Tests\Unit\DataModel\Float;

use Zerotoprod\DataModel\DataModel;

class Child
{
    use DataModel;

    public const float = 'float';
    public float $float;
}