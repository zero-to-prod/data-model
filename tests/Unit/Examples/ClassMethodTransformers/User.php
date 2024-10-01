<?php

namespace Tests\Unit\Examples\ClassMethodTransformers;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

readonly class User
{
    use DataModel;

    public string $first_name;
    public string $last_name;
    public string $fullName;

    #[Describe('last_name')]
    private function lastName(?string $value, array $context): string
    {
        return strtoupper($value);
    }

    #[Describe('fullName')]
    private function fullName(null $value, array $context): string
    {
        return "{$context['first_name']} {$context['last_name']}";
    }
}