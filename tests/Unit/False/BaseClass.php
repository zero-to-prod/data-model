<?php

namespace Tests\Unit\False;

use Zerotoprod\DataModel\DataModel;

class BaseClass
{
    use DataModel;

    public const false = 'false';
    public const Child = 'Child';

    /** @var false $false */
    public $false;

    /** @var Child $Child */
    public $Child;
}