<?php

namespace Tests\Unit\Bool;

use Zerotoprod\DataModel\DataModel;

class BaseClass
{
    use DataModel;

    public const bool = 'bool';
    public const Child = 'Child';

    /** @var   bool    $bool    */
    public $bool;

    /**   @var   Child   $Child   */
    public $Child;
}