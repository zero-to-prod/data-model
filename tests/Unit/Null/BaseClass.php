<?php

namespace Tests\Unit\Null;

use Zerotoprod\DataModel\DataModel;

class BaseClass
{
    use DataModel;

    public const null = 'null';
    public const Child = 'Child';

    /** @var null $null */
    public $null;

    /** @var Child $Child */
    public $Child;
}