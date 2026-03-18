<?php

namespace Tests\Unit\Examples\Assign;

use Tests\Unit\Examples\ExtendsTrait\DataModel;
use Zerotoprod\DataModel\Describe;

class User
{
    use DataModel;

    #[Describe(['assign' => ['role' => 'admin']])]
    public array $config;

    #[Describe(['assign' => [self::class, 'account']])]
    public string $account;

    #[Describe(['assign' => [self::class, 'permissions']])]
    public array $permissions;

    public static function account($value, array $context): string
    {
        return 'service-account';
    }

    public static function permissions($value, array $context, ?\ReflectionAttribute $Attribute, \ReflectionProperty $Property): array
    {
        return ['read', 'write'];
    }
}
