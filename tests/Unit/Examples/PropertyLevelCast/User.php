<?php

namespace Tests\Unit\Examples\PropertyLevelCast;

use ReflectionAttribute;
use ReflectionProperty;
use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

class User
{
    use DataModel;

    #[Describe(['cast' => [__CLASS__, 'firstName'], 'function' => 'strtoupper'])]
    public string $first_name;

    #[Describe(['cast' => 'uppercase'])]
    public string $last_name;

    #[Describe(['cast' => [__CLASS__, 'fullName']])]
    public string $full_name;

    private static function firstName(
        mixed $value,
        array $context,
        ?ReflectionAttribute $ReflectionAttribute,
        ReflectionProperty $ReflectionProperty
    ): string {
        return $ReflectionAttribute->getArguments()[0]['function']($value);
    }

    public static function fullName($value, array $context): string
    {
        return "{$context['first_name']} {$context['last_name']}";
    }
}