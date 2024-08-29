<?php

namespace Tests\Unit\Bool;

use Zerotoprod\DataModel\DataModel;

class Child
{
    use DataModel;

    public const bool = 'bool';

    /** @var bool $bool */
    public $bool;
}