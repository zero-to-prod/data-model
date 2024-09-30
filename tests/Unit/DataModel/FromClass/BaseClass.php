<?php

namespace Tests\Unit\DataModel\FromClass;

use Zerotoprod\DataModel\DataModel;

readonly class BaseClass
{
    use DataModel;

    public const Child = 'Child';
    public const ChildWithoutFrom = 'ChildWithoutFrom';

    public Child $Child;
    public ChildWithoutFrom $ChildWithoutFrom;
}