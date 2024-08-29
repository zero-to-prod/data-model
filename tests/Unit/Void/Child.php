<?php

namespace Tests\Unit\Void;

use Zerotoprod\DataModel\DataModel;

class Child
{
    use DataModel;

    public const void = 'void';

    /* @var void $void */
    public $void;
}