<?php

namespace Tests\Unit\FromDataModel;

use Zerotoprod\DataModel\DataModel;

class BaseClass
{
    use DataModel;

    public const Child = 'Child';

    /** @var Child $Child */
    public $Child;
}