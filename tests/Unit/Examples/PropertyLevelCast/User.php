<?php

namespace Tests\Unit\Examples\PropertyLevelCast;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;
readonly class User
{
    use DataModel;

    #[Describe(['cast' => [__CLASS__, 'firstName'], 'function' => 'strtoupper'])]
    public string $first_name;

    #[Describe(['cast' => 'uppercase'])]
    public string $last_name;

    #[Describe(['cast' => [__CLASS__, 'fullName']])]
    public string $full_name;


    private static function firstName(mixed $value, array $context, array $args): string
    {
        return $args[0]['function']($value);
    }

    public static function fullName(null $value, array $context): string
    {
        return "{$context['first_name']} {$context['last_name']}";
    }
}