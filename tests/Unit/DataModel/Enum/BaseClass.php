<?php

namespace Tests\Unit\DataModel\Enum;

use Zerotoprod\DataModel\DataModel;

readonly class BaseClass
{
    use DataModel;

    public const string = 'string';
    public const StringEnum = 'StringEnum';
    public const enum_value = 'enum_value';
    public const IntEnum = 'IntEnum';

    public string $string;
    public StringEnum $StringEnum;
    public StringEnum $enum_value;
    public IntEnum $IntEnum;
}