<?php

namespace Tests\Unit\Examples\ClassMethodTransformers;

use Zerotoprod\DataModel\DataModel;

readonly class User
{
    use DataModel;

    public string $first_name;
    public string $last_name;
    public string $fullName;

    private function last_name(?string $value, array $context): string
    {
        return strtoupper($value);
    }

    private function fullName(null $value, array $context): string
    {
        return "{$context['first_name']} {$context['last_name']}";
    }
}