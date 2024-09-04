<?php

namespace Tests\Unit\DataModel\Recursion;

use Zerotoprod\DataModel\DataModel;

class ShortNamespaceChild
{
    use DataModel;

    public const id = 'id';
    public const name = 'name';

    /* @var int $id */
    public $id;
    /* @var string $name */
    public $name;
}