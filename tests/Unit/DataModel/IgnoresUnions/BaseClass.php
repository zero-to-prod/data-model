<?php

namespace Tests\Unit\DataModel\IgnoresUnions;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

class BaseClass
{
    use DataModel;

    public const id = 'id';
    public const required = 'required';
    public const no_type = 'no_type';
    public int|string $id;
    #[Describe(['required' => true])]
    public int|string $required;

    #[Describe(['required' => true])]
    public $no_type;
}