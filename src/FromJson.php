<?php

namespace Zerotoprod\DataModel;

use InvalidArgumentException;

/**
 * The `FromJson` trait adds a `fromJson` method to instantiate a class from a JSON string.
 * It decodes JSON into an associative array and maps it to class properties using the `from` method.
 * Throws an `InvalidArgumentException` if decoding fails.
 *
 * @package Zerotoprod\DataModel
 */
trait FromJson
{
    /**
     * Instantiates the class from a JSON string by decoding it into an associative array
     * and mapping the array to class properties using the `from` method.
     *
     * @param string $value JSON string to decode.
     * @param int $depth Maximum decoding depth (default: 512).
     * @param int $flags JSON decode options (default: 0).
     */
    public static function fromJson(string $value, int $depth = 512, int $flags = 0): self
    {
        $decoded = json_decode($value, true, $depth, $flags);

        if (!$decoded) {
            throw new InvalidArgumentException("Failed to decode JSON: $value");
        }

        return self::from($decoded);
    }
}