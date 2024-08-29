<?php

namespace Tests\Unit\Never;

use Zerotoprod\DataModel\DataModel;

class Child
{
    use DataModel;

    public const never = 'never';

    /* @var never $never */
    public $never;
}