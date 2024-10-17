<?php

namespace Tests\Unit\DataModel\FromStdClass;

use stdClass;
use Zerotoprod\DataModel\DataModel;

class BaseClass
{
    use DataModel;

    public const id = 'id';
    public const Child = 'Child';
    public const stdClass = 'stdClass';
    public const stdClassWithoutBackslash = 'stdClassWithoutBackslash';

    public int $id;
    public Child $Child;
    public stdClass $stdClass;
    public stdClass $stdClassWithoutBackslash;
}