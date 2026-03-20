<?php

namespace Tests\Unit\Describe\Extra;

use ReflectionAttribute;
use ReflectionProperty;
use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

class ExtraCastModel
{
    use DataModel;

    #[Describe(['cast' => [self::class, 'applyFn'], 'function' => 'strtoupper'])]
    public string $name;

    public static function applyFn($value, array $context, ?ReflectionAttribute $Attribute, ReflectionProperty $Property): string
    {
        $fn = $Attribute->getArguments()[0]['function'];

        return $fn($value);
    }
}
