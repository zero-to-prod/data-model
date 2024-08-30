<?php

namespace Tests\Unit\FromJson;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\FromJson;

class BaseClass
{
    use DataModel;
    use FromJson;

    public const id = 'id';
    public const name = 'name';
    /** @var int $id */
    public $id;
    /** @var string $name */
    public $name;
}