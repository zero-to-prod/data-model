<?php

namespace Tests\Unit\Describe\Ignore;

use ReflectionAttribute;
use ReflectionProperty;
use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

class BaseClass
{
    use DataModel;

    public const skip = 'skip';
    public const value = 'value';

    /** `ignore` resolved dynamically from another context value. */
    #[Describe(['ignore' => [self::class, 'shouldIgnore']])]
    public string $value = 'untouched';

    public static function shouldIgnore(
        $value,
        array $context,
        ?ReflectionAttribute $Attribute,
        ReflectionProperty $Property
    ): bool {
        return (bool)($context[self::skip] ?? false);
    }
}
