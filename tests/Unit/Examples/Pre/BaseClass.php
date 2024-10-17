<?php

namespace Tests\Unit\Examples\Pre;

use Zerotoprod\DataModel\Describe;

class BaseClass
{
    use \Zerotoprod\DataModel\DataModel;

    public const int = 'int';

    #[Describe(['pre' => [self::class, 'pre'], 'message' => 'Value too large.'])]
    public int $int;

    public static function pre($value, array $context, ?\ReflectionAttribute $Attribute, \ReflectionProperty $Property): void
    {
        if ($value > 10) {
            throw new \RuntimeException($Attribute->getArguments()[0]['message']);
        }
    }
}