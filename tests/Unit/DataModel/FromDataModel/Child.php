<?php

namespace Tests\Unit\DataModel\FromDataModel;

use Zerotoprod\DataModel\DataModel;

readonly class Child
{
    use DataModel;

    public const id = 'id';
    public int $id;
}