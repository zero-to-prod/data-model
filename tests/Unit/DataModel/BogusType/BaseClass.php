<?php

namespace Tests\Unit\DataModel\BogusType;

use Tests\Unit\BogusType\bogus;
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