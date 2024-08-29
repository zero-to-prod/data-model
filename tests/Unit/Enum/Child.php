<?php

namespace Tests\Unit\Enum;

use Zerotoprod\DataModel\DataModel;

class Child
{
    use DataModel;

    public const enum = 'enum';

    /* @var enum $enum */
    public $enum;
}