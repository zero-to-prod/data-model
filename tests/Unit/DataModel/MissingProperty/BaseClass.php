<?php

namespace Tests\Unit\DataModel\MissingProperty;

use Zerotoprod\DataModel\DataModel;

/**
 * @property $name
 */
class BaseClass
{
    use DataModel;

    public const id = 'id';

    /** @var int|string $id */
    public $id;

}