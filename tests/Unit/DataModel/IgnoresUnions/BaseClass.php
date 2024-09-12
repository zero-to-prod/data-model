<?php

namespace Tests\Unit\DataModel\IgnoresUnions;

use Zerotoprod\DataModel\DataModel;

readonly class BaseClass
{
    use DataModel;

    public const id = 'id';
    public int|string $id;
}