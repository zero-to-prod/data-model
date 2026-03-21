<?php

namespace Tests\Unit\DataModel\FromString;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

class BaseClass
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

    public static function defaultCallable(): string
    {
        return 'callable_default';
    }

    public static function assignCallable(): string
    {
        return 'callable_assign';
    }
}
