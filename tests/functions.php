<?php

if (!function_exists('parse')) {
    function parse($value): string
    {
        return $value . 'bar';
    }
}