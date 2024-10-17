<?php

namespace Tests\Unit\Describe\TaggedMethodException;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

class BaseClass
{
    use DataModel;

    public const override = 'override';

    #[Describe(self::override)]
    public function altOverride(): string
    {
        return 'bar';
    }

    #[Describe(self::override)]
    public function altOverrideFalse(): string
    {
        return 'foo';
    }
}