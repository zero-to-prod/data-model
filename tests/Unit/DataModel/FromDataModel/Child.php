<?php

namespace Tests\Unit\DataModel\FromDataModel;

use Zerotoprod\DataModel\DataModel;

class Child
{
    use DataModel;

    public const id = 'id';

    /* @var int $id */
    public $id;
}