<?php

namespace Tests\Unit\DataModel\FromArray;

use Zerotoprod\DataModel\DataModel;

class Child
{
    use DataModel;

    public const array = 'array';

    /** @var array $array */
    public $array;
}