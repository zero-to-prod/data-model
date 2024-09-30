<?php

namespace Tests\Unit\DataModel\DynamicSet;

use Zerotoprod\DataModel\DataModel;

readonly class BaseClass
{
    use DataModel;

    public const foo = 'foo';
    public const bar = 'bar';
    public string $foo;
    public string $bar;

    private function foo($value, $context): string
    {
        return $value.$context[self::bar];
    }
}