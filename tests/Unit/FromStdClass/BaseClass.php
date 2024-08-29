<?php

namespace Tests\Unit\FromStdClass;

use stdClass;
use Zerotoprod\DataModel\DataModel;

class BaseClass
{
    use DataModel;

    public const id = 'id';
    public const Child = 'Child';
    public const stdClass = 'stdClass';
    public const stdClassWithoutBackslash = 'stdClassWithoutBackslash';

    /**
     * @var int $id
     */
    public $id;

    /**
     * @var Child $Child
     */
    public $Child;

    /**
     * @var \stdClass $stdClass
     */
    public $stdClass;

    /**
     * @var stdClass $stdClassWithoutBackslash
     */
    public $stdClassWithoutBackslash;
}