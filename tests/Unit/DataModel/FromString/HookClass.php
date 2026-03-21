<?php

namespace Tests\Unit\DataModel\FromString;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

class HookClass
{
    use DataModel;

    public static int $pre_called = 0;
    public static int $post_called = 0;

    #[Describe(['pre' => [self::class, 'preHook'], 'nullable' => true])]
    public ?string $pre_prop;

    #[Describe(['post' => [self::class, 'postHook'], 'default' => 'value'])]
    public string $post_prop;

    public static function preHook(mixed $value, array $context, ?\ReflectionAttribute $Attribute, \ReflectionProperty $Property): void
    {
        self::$pre_called++;
    }

    public static function postHook(mixed $value, array $context, ?\ReflectionAttribute $Attribute, \ReflectionProperty $Property): void
    {
        self::$post_called++;
    }
}
