<?php

namespace Tests\Unit\Describe\Extra;

use ReflectionAttribute;
use ReflectionProperty;
use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

class ExtraInstanceModel
{
    use DataModel;

    #[Describe(['cast' => [self::class, 'applyFn'], 'function' => 'strtoupper'])]
    public string $name;

    public static function applyFn($value, array $context, ?ReflectionAttribute $Attribute, ReflectionProperty $Property): string
    {
        $Describe = $Attribute->newInstance();
        $fn = $Describe->extra['function'];

        return $fn($value);
    }
}
