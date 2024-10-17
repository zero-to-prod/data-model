<?php

namespace Tests\Unit\DataModel\FromDataModel;

use Zerotoprod\DataModel\DataModel;

class BaseClass
{
    use DataModel;

    public const id = 'id';
    public const Child = 'Child';
    public const NullChild = 'NullChild';
    public int $id;
    public Child $Child;
    public Child $NullChild;
}