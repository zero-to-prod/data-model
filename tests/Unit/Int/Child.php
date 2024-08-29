<?php

namespace Tests\Unit\Int;

use Zerotoprod\DataModel\DataModel;

class Child
{
    use DataModel;

    public const int = 'int';

    /* @var int $int */
    public $int;
}