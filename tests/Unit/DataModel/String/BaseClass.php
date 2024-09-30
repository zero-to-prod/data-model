<?php

namespace Tests\Unit\DataModel\String;

use Zerotoprod\DataModel\DataModel;

readonly class BaseClass
{
    use DataModel;

    public const string = 'string';
    public const Child = 'Child';

    public string $string;
    public Child $Child;
}