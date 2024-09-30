<?php

namespace Tests\Unit\DataModel\Enum;

use Zerotoprod\DataModel\DataModel;

enum StringEnum: string
{
    use DataModel;

    case string = 'string';
}