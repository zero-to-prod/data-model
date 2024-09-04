<?php

namespace Tests\Unit\DataModel\Int;

use Zerotoprod\DataModel\DataModel;

class BaseClass
{
    use DataModel;

    public const int = 'int';
    public const Child = 'Child';

    /** @var int $int */
    public $int;

    /** @var Child $Child */
    public $Child;
}