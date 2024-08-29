<?php

namespace Tests\Unit\Never;

use Zerotoprod\DataModel\DataModel;

class BaseClass
{
    use DataModel;

    public const never = 'never';
    public const Child = 'Child';

    /** @var never $never */
    public $never;

    /** @var Child $Child */
    public $Child;
}