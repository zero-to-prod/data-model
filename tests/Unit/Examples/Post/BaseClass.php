<?php

namespace Tests\Unit\Examples\Post;

use Zerotoprod\DataModel\Describe;

class BaseClass
{
    use \Zerotoprod\DataModel\DataModel;

    public const int = 'int';

    #[Describe([
        'post' => [self::class, 'post'],
        'message' => 'Value too large.',
    ])]
    public int $int;

    public static function post($value, array $context, ?\ReflectionAttribute $Attribute, \ReflectionProperty $Property): void
    {
        if ($value > 10) {
            throw new \RuntimeException($value.$Attribute->getArguments()[0]['message']);
        }
    }
}