<?php

namespace Tests\Unit\Void;

use Zerotoprod\DataModel\DataModel;

class BaseClass
{
    use DataModel;

    public const void = 'void';
    public const Child = 'Child';

    /** @var void $void */
    public $void;

    /** @var Child $Child */
    public $Child;
}