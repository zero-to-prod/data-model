<?php

namespace Tests\Unit\IgnoresUnions;

use Tests\TestCase;
use Zerotoprod\DataModel\DataModel;

class IgnoresUnionsTest extends TestCase
{
    /**
     * @test
     *
     * @see DataModel
     */
    public function ignores_unions(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::id => 1,
        ]);

        $this->assertEquals(1, $BaseClass->id);

        $BaseClass = BaseClass::from([
            BaseClass::id => '1',
        ]);

        $this->assertEquals('1', $BaseClass->id);
    }
}