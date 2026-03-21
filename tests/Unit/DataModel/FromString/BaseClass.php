<?php

namespace Tests\Unit\DataModel\FromString;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

readonly class BaseClass
{
    use DataModel;

    #[Describe(['nullable' => true])]
    public ?string $nullable_prop;

    #[Describe(['default' => 'fallback'])]
    public string $default_prop;

    #[Describe(['default' => [self::class, 'defaultCallable']])]
    public string $default_callable_prop;

    #[Describe(['assign' => 'fixed'])]
    public string $assign_prop;

    #[Describe(['assign' => [self::class, 'assignCallable']])]
    public string $assign_callable_prop;

    #[Describe(['ignore' => true])]
    public string $ignored_prop;

    public static int $pre_called = 0;
    public static int $post_called = 0;

    #[Describe(['pre' => [self::class, 'preHook'], 'nullable' => true])]
    public ?string $pre_prop;

    #[Describe(['post' => [self::class, 'postHook'], 'nullable' => true])]
    public ?string $post_prop;

    public static function defaultCallable(): string
    {
        return 'callable_default';
    }

    public static function assignCallable(): string
    {
        return 'callable_assign';
    }

    public static function preHook(mixed $value, array $context, ?\ReflectionAttribute $Attribute, \ReflectionProperty $Property): void
    {
        self::$pre_called++;
    }

    public static function postHook(mixed $value, array $context, ?\ReflectionAttribute $Attribute, \ReflectionProperty $Property): void
    {
        self::$post_called++;
    }
}
