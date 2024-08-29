<?php

namespace Tests\Unit\True;

use Zerotoprod\DataModel\DataModel;

class Child
{
    use DataModel;

    public const true = 'true';

    /* @var true $true */
    public $true;
}