<?php

namespace Tests\Unit\DataModel;

use Zerotoprod\DataModel\DataModel;

class Child
{
    use DataModel;

    public const float = 'float';

    /* @var float $float */
    public $float;
}