<?php

namespace Tests\Unit\DataModel\Float;

use Zerotoprod\DataModel\DataModel;

class BaseClass
{
    use DataModel;

    public const float = 'float';
    public const Child = 'Child';

    /** @var float $float */
    public $float;

    /** @var Child $Child */
    public $Child;
}