<?php

namespace Tests\Unit\True;

use Zerotoprod\DataModel\DataModel;

class BaseClass
{
    use DataModel;

    public const true = 'true';
    public const Child = 'Child';

    /** @var true $true */
    public $true;

    /** @var Child $Child */
    public $Child;
}