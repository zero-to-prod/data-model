<?php

namespace Tests\Unit\DataModel\FromArray;

use Zerotoprod\DataModel\DataModel;

class BaseClass
{
    use DataModel;

    public const array = 'array';
    public const object = 'object';
    public const Child = 'Child';

    /** @var array $array */
    public $array;

    /** @var Child $Child */
    public $Child;
    /** @var array $object */
    public $object;
}