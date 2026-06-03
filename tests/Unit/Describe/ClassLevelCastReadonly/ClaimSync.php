<?php

namespace Tests\Unit\Describe\ClassLevelCastReadonly;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

#[Describe([
    'cast' => [
        'string' => 'strtolower'
    ]
])]
class ClaimSync
{
    use DataModel;

    public const name = 'name';

    readonly public string $name;
}