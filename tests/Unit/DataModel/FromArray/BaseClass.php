<?php

namespace Tests\Unit\DataModel\FromArray;

use Zerotoprod\DataModel\DataModel;

readonly class BaseClass
{
    use DataModel;

    public const array = 'array';
    public const object = 'object';
    public const Child = 'Child';

    public array $array;

    public Child $Child;
    public array $object;
}