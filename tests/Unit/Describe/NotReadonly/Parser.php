<?php

namespace Tests\Unit\Describe\NotReadonly;

use DateTime;

class Parser
{
    public static function arbitrary($value, $context = null): string
    {
        return $context !== null ? $context['string'] ?? null.'bar' : 'bar';
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