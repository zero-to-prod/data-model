<?php

namespace Tests\Unit\DataModel\FromClass;

use Zerotoprod\DataModel\DataModel;

class BaseClass
{
    use DataModel;

    public const Child = 'Child';
    public const ChildWithoutFrom = 'ChildWithoutFrom';

    /** @var Child $Child */
    public $Child;

    /** @var ChildWithoutFrom $ChildWithoutFrom */
    public $ChildWithoutFrom;
}