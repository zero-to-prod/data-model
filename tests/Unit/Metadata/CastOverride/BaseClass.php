<?php

namespace Tests\Unit\Metadata\CastOverride;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

#[Describe(describe)]
readonly class BaseClass
{
    use DataModel;

    public const name = 'name';

    #[Describe(['cast' => [Helpers::class, 'setFooString']])]
    public string $name;
}