<?php

namespace Tests\Unit\Metadata\CastOverride;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;
use Zerotoprod\DataModel\Metadata;

#[Metadata(metadata)]
readonly class BaseClass
{
    use DataModel;

    public const name = 'name';

    #[Describe(['target' => [Helpers::class, 'setFooString']])]
    public string $name;
}