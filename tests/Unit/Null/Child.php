<?php

namespace Tests\Unit\Null;

use Zerotoprod\DataModel\DataModel;

class Child
{
    use DataModel;

    public const null = 'null';

    /* @var null $null */
    public $null;
}