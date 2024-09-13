<?php

namespace Tests\Unit\Describe\ClassDynamic;

use DateTime;

readonly class Parser
{
    public static function arbitrary($value, $context = null): string
    {
        return $context !== null ? $context['string'].'bar' : 'bar';
    }

    public static function parse($value, $context = null): string
    {
        return 'bar';
    }

    public static function dateTime($value): DateTime
    {
        return new DateTime($value);
    }
}