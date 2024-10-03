<?php

namespace Tests\Unit\Examples\MethodLevelCast;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

readonly class User
{
    use DataModel;

    public string $first_name;
    public string $last_name;
    public string $fullName;

    #[Describe('last_name')]
    public function lastName(?string $value, array $context): string
    {
        return strtoupper($value);
    }

    #[Describe('fullName')]
    public function fullName(null $value, array $context): string
    {
        return "{$context['first_name']} {$context['last_name']}";
    }
}