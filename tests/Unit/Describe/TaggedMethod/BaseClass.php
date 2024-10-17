<?php

namespace Tests\Unit\Describe\TaggedMethod;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

class BaseClass
{
    use DataModel;

    public const override = 'override';
    public string $override;

    public function override(): string
    {
        return 'foo';
    }

    #[Describe(self::override)]
    #[Bogus()]
    public function altOverride(): string
    {
        return 'bar';
    }

    #[Describe('bogus')]
    public function bogus(): string
    {
        return 'foo';
    }
}