<?php

namespace Tests\Unit\FromDataModel;

use Zerotoprod\DataModel\DataModel;

class BaseClass
{
    use DataModel;

    public const id = 'id';
    public const Child = 'Child';
    public const NullChild = 'NullChild';
    /** @var int $id */
    public $id;
    /** @var Child $Child */
    public $Child;
    /** @var Child $NullChild */
    public $NullChild;
}