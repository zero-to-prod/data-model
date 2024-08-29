<?php

namespace Tests\Unit\Instance;

use Zerotoprod\DataModel\DataModel;

class BaseClass
{
    use DataModel;

    public const id = 'id';
    public const name = 'name';
    /** @var int $id */
    public $id;
    /** @var string $name */
    public $name;
}