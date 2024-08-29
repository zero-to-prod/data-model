<?php

namespace Tests\Unit\Iterable;

use Zerotoprod\DataModel\DataModel;

class BaseClass
{
    use DataModel;

    public const iterable = 'iterable';
    public const Child = 'Child';

    /** @var iterable $iterable */
    public $iterable;

    /** @var Child $Child */
    public $Child;
}