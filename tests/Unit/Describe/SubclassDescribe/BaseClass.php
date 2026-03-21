<?php

namespace Tests\Unit\Describe\SubclassDescribe;

use Zerotoprod\DataModel\DataModel;

class BaseClass
{
    use DataModel;

    #[ChildDescribe(['nullable' => true])]
    public ?string $nullable_prop;

    #[ChildDescribe(['default' => 'fallback'])]
    public string $default_prop;

    #[ChildDescribe(['default' => [self::class, 'defaultCallable']])]
    public string $default_callable_prop;

    #[ChildDescribe(['assign' => 'fixed'])]
    public string $assign_prop;

    #[ChildDescribe(['assign' => [self::class, 'assignCallable']])]
    public string $assign_callable_prop;

    #[ChildDescribe(['ignore' => true])]
    public string $ignored_prop;

    public static function defaultCallable(): string
    {
        return 'callable_default';
    }

    public static function assignCallable(): string
    {
        return 'callable_assign';
    }
}
