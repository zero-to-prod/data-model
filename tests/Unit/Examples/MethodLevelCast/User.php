<?php

namespace Tests\Unit\Examples\MethodLevelCast;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

class User
{
    use DataModel;

    public string $first_name;
    public string $last_name;
    public string $fullName;

    #[Describe('last_name')]
    public function lastName(?string $value, array $context, ?\ReflectionAttribute $Attribute, \ReflectionProperty $Property): string
    {
        return strtoupper($value ?? '');
    }

    #[Describe('fullName')]
    public function fullName($value, array $context, ?\ReflectionAttribute $Attribute, \ReflectionProperty $Property): string
    {
        $last_name = $context['last_name'] ?? null;
        $first_name = $context['first_name'] ?? null;

        return "$first_name $last_name";
    }
}