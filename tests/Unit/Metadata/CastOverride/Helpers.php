<?php

namespace Tests\Unit\Metadata\CastOverride;

use DateMalformedStringException;
use DateTimeImmutable;

readonly class Helpers
{

    public static function setBarString(): string
    {
        return 'bar';
    }

    public static function setFooString(): string
    {
        return 'foo';
    }
}