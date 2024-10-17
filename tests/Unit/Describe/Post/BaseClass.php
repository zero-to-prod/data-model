<?php

namespace Tests\Unit\Describe\Post;

use ReflectionAttribute;
use ReflectionProperty;
use RuntimeException;
use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

class BaseClass
{
    use DataModel;

    public const int = 'int';

    #[Describe([
        'cast' => [self::class, 'increment'],
        'post' => [self::class, 'post'],
        'message' => 'Value too large.',
    ])]
    public int $int;

    public static function increment($value, array $context, ?ReflectionAttribute $ReflectionAttribute, ReflectionProperty $ReflectionProperty): int
    {
       return ++$value;
    }

    public static function post($value, array $context, ?ReflectionAttribute $ReflectionAttribute, ReflectionProperty $ReflectionProperty): void
    {
        if ($value > 10) {
            throw new RuntimeException($value.$ReflectionAttribute->getArguments()[0]['message']);
        }
    }
}