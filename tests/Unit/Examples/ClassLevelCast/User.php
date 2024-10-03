<?php

namespace Tests\Unit\Examples\ClassLevelCast;

use DateTimeImmutable;
use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

#[Describe([
    'cast' => [
        'string' => 'uppercase',
        DateTimeImmutable::class => [__CLASS__, 'toDateTimeImmutable'],
    ]
])]
readonly class User
{
    use DataModel;

    public string $first_name;
    public DateTimeImmutable $registered;

    public static function toDateTimeImmutable(string $value, array $context): DateTimeImmutable
    {
        return new DateTimeImmutable($value);
    }
}