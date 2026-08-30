<?php

namespace Tests\Unit\Describe\Required\Callable;

use ReflectionAttribute;
use ReflectionProperty;
use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

class BaseClass
{
    use DataModel;

    public const flag = 'flag';
    public const required = 'required';

    /** `required` resolved dynamically from another context value. */
    #[Describe(['required' => [self::class, 'isRequired']])]
    public string $required;

    public static function isRequired(
        $value,
        array $context,
        ?ReflectionAttribute $Attribute,
        ReflectionProperty $Property
    ): bool {
        return (bool)($context[self::flag] ?? false);
    }
}
