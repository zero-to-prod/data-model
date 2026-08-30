<?php

namespace Tests\Unit\Describe\From;

use ReflectionAttribute;
use ReflectionProperty;
use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

class BaseClass
{
    use DataModel;

    public const first_name = 'first_name';
    public const legacy_name = 'legacy_name';

    /** `from` resolved dynamically: prefer `first_name`, fall back to `legacy_name`. */
    #[Describe(['from' => [self::class, 'resolveKey']])]
    public string $name;

    public static function resolveKey(
        string $propertyName,
        array $context,
        ?ReflectionAttribute $Attribute,
        ReflectionProperty $Property
    ): string {
        return isset($context[self::first_name]) ? self::first_name : self::legacy_name;
    }
}
