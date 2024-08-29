<?php

namespace Tests\Unit\FromArray;

use Zerotoprod\DataModel\DataModel;

class Child
{
    use DataModel;

    public const array = 'array';

    /** @var array $array */
    public $array;
}