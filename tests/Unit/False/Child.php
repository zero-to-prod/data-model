<?php

namespace Tests\Unit\False;

use Zerotoprod\DataModel\DataModel;

class Child
{
    use DataModel;

    public const false = 'false';

    /* @var false $false */
    public $false;
}