<?php

namespace Zerotoprod\DataModel\Helpers;

/**
 * The `Str` helper class defines constants for pattern matching and type identification
 * within the DataModel package, aiding in parsing and handling type annotations in doc comments.
 *
 * @package Zerotoprod\DataModel\Helpers
 */
class Str
{
    public const pattern = '/@var\s+(?P<native_type>string|array|int|bool|object|float|stdClass|\\\stdClass|[\w\\\\]+\|[\w\\\\]+)?\s*(?P<type>[\\\\\w]+)?/';
    public const type = 'type';
    public const from = 'from';
    public const native_type = 'native_type';
    public const string = 'string';
    public const array = 'array';
    public const int = 'int';
    public const bool = 'bool';
    public const object = 'object';
    public const float = 'float';
    public const stdClass = 'stdClass';
    public const _stdClass = '\stdClass';
}
