<?php

namespace Tests\Unit\DataModel\String;

use Zerotoprod\DataModel\DataModel;

class BaseClass
{
    use DataModel;

    public const string = 'string';
    public const Child = 'Child';

    /** @var string $string */
    public $string;

    /** @var Child $Child */
    public $Child;
}