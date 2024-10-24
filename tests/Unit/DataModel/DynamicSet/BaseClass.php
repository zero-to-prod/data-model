<?php

namespace Tests\Unit\DataModel\DynamicSet;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

class BaseClass
{
    use DataModel;

    public const foo = 'foo';
    public const bar = 'bar';
    public string $foo;
    public string $bar;

    #[Describe(self::foo)]
    private function foo($value, $context): string
    {
        return $value.$context[self::bar];
    }
}