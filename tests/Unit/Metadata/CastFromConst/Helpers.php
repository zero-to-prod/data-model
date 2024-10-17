<?php

namespace Tests\Unit\Metadata\CastFromConst;

use DateMalformedStringException;
use DateTimeImmutable;

class Helpers
{
    /**
     * @throws DateMalformedStringException
     */
    public static function dateTimeImmutable($value): DateTimeImmutable
    {
        return new DateTimeImmutable($value);
    }

    public static function setString(): string
    {
        return 'bar';
    }
}