<?php

namespace Tests\Unit\DataModel\BogusType;

use Tests\Unit\BogusType\bogus;
use Zerotoprod\DataModel\DataModel;

class Child
{
    use DataModel;

    public const enum = 'enum';

    /* @var bogus $enum */
    public $enum;
}