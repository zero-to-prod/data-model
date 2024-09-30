<?php

namespace Tests\Unit\DataModel\Enum;

use Zerotoprod\DataModel\DataModel;

readonly class BaseClass
{
    use DataModel;

    public const string = 'string';
    public const StringEnum = 'StringEnum';
    public const IntEnum = 'IntEnum';

    public string $string;
    public StringEnum $StringEnum;
    public IntEnum $IntEnum;
}