<?php

namespace Tests\Unit\Metadata\Cast;

use DateTimeImmutable;
use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Metadata;

#[Metadata([
    'cast' => [
        DateTimeImmutable::class => [Helpers::class, 'dateTimeImmutable'],
        'string' => [Helpers::class, 'setString'],
    ]
])]
readonly class BaseClass
{
    use DataModel;

    public const DateTimeImmutable = 'DateTimeImmutable';
    public const name = 'name';

    public DateTimeImmutable $DateTimeImmutable;
    public string $name;
}