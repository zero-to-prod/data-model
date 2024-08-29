<?php

namespace Tests\Unit\Iterable;

use Zerotoprod\DataModel\DataModel;

class Child
{
    use DataModel;

    public const iterable = 'iterable';

    /* @var iterable $iterable */
    public $iterable;
}