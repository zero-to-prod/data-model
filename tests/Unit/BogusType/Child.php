<?php

namespace Tests\Unit\BogusType;

use Zerotoprod\DataModel\DataModel;

class Child
{
    use DataModel;

    public const enum = 'enum';

    /* @var bogus $enum */
    public $enum;
}