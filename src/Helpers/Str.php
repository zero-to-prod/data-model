<?php

namespace Zerotoprod\DataModel\Helpers;

class Str
{
    public const pattern = '/@var\s+(?P<type>int|float|null|never|void|enum|bool|true|false|string|array|iterable|object|stdClass|\\\stdClass|mixed|resource|numeric|[\w\\\\]+\|[\w\\\\]+)?\s*(?P<class_type>[\\\\\w]+)?/';
    public const class_type = 'class_type';
    public const from = 'from';
    public const type = 'type';
    public const string = 'string';
    public const array = 'array';
    public const int = 'int';
    public const bool = 'bool';
    public const object = 'object';
    public const float = 'float';
}