<?php

namespace Tests\Unit\Enum;

use Zerotoprod\DataModel\DataModel;

class BaseClass
{
    use DataModel;

    public const enum = 'enum';
    public const Child = 'Child';

    /** @var enum $enum */
    public $enum;

    /** @var Child $Child */
    public $Child;
}