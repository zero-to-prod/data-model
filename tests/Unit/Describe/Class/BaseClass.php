<?php

namespace Tests\Unit\Describe\Class;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

readonly class BaseClass
{
    use DataModel;

    public const string = 'string';

    #[Describe(['cast' => Parser::class])]
    public string $string;
}