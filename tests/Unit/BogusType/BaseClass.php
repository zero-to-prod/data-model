<?php

namespace Tests\Unit\BogusType;

use Zerotoprod\DataModel\DataModel;

class BaseClass
{
    use DataModel;

    public const enum = 'enum';
    public const Child = 'Child';

    /** @var bogus $enum */
    public $enum;

    /** @var Child $Child */
    public $Child;
}