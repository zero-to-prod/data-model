<?php

namespace Tests\Unit\Metadata\Cast;

use DateTimeImmutable;

class Helpers
{
    public static function dateTimeImmutable($value): DateTimeImmutable
    {
        return new DateTimeImmutable($value);
    }

    public static function setString(): string
    {
        return 'bar';
    }
}