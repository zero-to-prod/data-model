<?php

namespace Tests\Unit\IgnoresUnions;

use Zerotoprod\DataModel\DataModel;

class BaseClass
{
    use DataModel;

    public const id = 'id';

    /** @var int|string $id */
    public $id;

}