<?php

namespace Zerotoprod\DataModel;

/**
 * The `FromJson` trait adds a `fromJson` method to instantiate a class from a JSON string.
 * It decodes JSON into an associative array and maps it to class properties using the `from` method.
 *
 * @see https://github.com/zero-to-prod/data-model
 */
trait FromJson
{
    /**
     * Instantiates the class from a JSON string.
     *
     * Example
     *  ```
     *  MyClass::fromJson('{"json": 1}');
     *  ```
     *
     * @param  string  $value  JSON string to decode.
     * @param  int     $depth  Maximum decoding depth (default: 512).
     * @param  int     $flags  JSON decode options (default: 0).
     *
     * @see https://github.com/zero-to-prod/data-model
     */
    public static function fromJson(string $value, int $depth = 512, int $flags = 0): self
    {
        return self::from(json_decode($value, true, $depth, $flags));
    }

    /**
     * Instantiates the class from a JSON string.
     *
     * Returns null if decoding fails.
     *
     * Example
     *  ```
     *  MyClass::tryFromJson('{"json": 1}');
     *  ```
     *
     * @param  string  $value  JSON string to decode.
     * @param  int     $depth  Maximum decoding depth (default: 512).
     * @param  int     $flags  JSON decode options (default: 0).
     *
     * @see https://github.com/zero-to-prod/data-model
     */
    public static function tryFromJson(string $value, int $depth = 512, int $flags = 0): ?self
    {
        $decoded = json_decode($value, true, $depth, $flags);

        return $decoded ? self::from($decoded) : null;
    }
}
