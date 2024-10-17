<?php

namespace Tests\Unit\DataModel\Bool;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

class BaseClass
{
    use DataModel;

    public const bool = 'bool';
    public const bool_describe = 'bool_describe';
    public const bool_required = 'bool_required';
    public const Child = 'Child';

    public bool $bool;
    #[Describe('bogus')]
    public bool $bool_describe;
    #[Describe(['required' => true])]
    public bool $bool_required;
    public Child $Child;
}