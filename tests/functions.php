<?php

if (!function_exists('parse')) {
    function parse($value, $context): string
    {
        return $value . 'bar';
    }

    function parse_without_context($value): string
    {
        return $value . 'bar';
    }
}