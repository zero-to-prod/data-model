<?php

namespace Tests\Unit\DataModel\FromArray;

use Zerotoprod\DataModel\DataModel;

readonly class Child
{
    use DataModel;

    public const array = 'array';
    
    public array $array;
}