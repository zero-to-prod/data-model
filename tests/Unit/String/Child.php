<?php

namespace Tests\Unit\String;

use Zerotoprod\DataModel\DataModel;

class Child
{
    use DataModel;

    public const string = 'string';

    /* @var string $string */
    public $string;
}