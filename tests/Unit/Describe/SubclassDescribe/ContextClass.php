<?php

namespace Tests\Unit\Describe\SubclassDescribe;

use Zerotoprod\DataModel\DataModel;

class ContextClass
{
    use DataModel;

    #[ChildDescribe(['from' => 'remapped_key'])]
    public string $from_prop;

    #[ChildDescribe(['cast' => [self::class, 'castUpper']])]
    public string $cast_prop;

    #[ChildDescribe(['cast' => [self::class, 'castWithContext'], 'label' => 'test'])]
    public string $cast_four_param_prop;

    public static int $pre_called = 0;
    public static int $post_called = 0;

    #[ChildDescribe(['pre' => [self::class, 'preHook']])]
    public string $pre_prop;

    #[ChildDescribe(['post' => [self::class, 'postHook']])]
    public string $post_prop;

    #[ChildDescribe(['via' => [Child::class, 'create']])]
    public Child $via_prop;

    public static function castUpper(mixed $value): string
    {
        return strtoupper($value);
    }

    public static function castWithContext(mixed $value, array $context, ?\ReflectionAttribute $Attribute, \ReflectionProperty $Property): string
    {
        $extra = $Attribute->newInstance()->extra;

        return $value.'-'.$extra['label'];
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
