<?php

namespace Tests\Unit\DataModel\String;

use Zerotoprod\DataModel\DataModel;

class Child
{
    use DataModel;

    public const string = 'string';
    public string $string;
}