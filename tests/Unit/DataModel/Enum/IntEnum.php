<?php

namespace Tests\Unit\DataModel\Enum;

use Zerotoprod\DataModel\DataModel;

enum IntEnum: int
{
    use DataModel;

    case id = 1;
}