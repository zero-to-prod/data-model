<?php

namespace Tests\Unit\Describe\From;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

readonly class BaseClass
{
    use DataModel;

    public const string = 'string';

    #[Describe(['from' => Parser::class])]
    public string $string;
}