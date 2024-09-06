<?php

namespace Tests\Unit\DataModel\Float;

use Tests\TestCase;
use Zerotoprod\DataModel\DataModel;

class FloatTest extends TestCase
{
    /**
     * @test
     *
     * @see DataModel
     */
    public function float(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::float => '1.1',
            BaseClass::Child => [
                Child::float => '2.2'
            ],
        ]);

        $this->assertEquals(1.1, $BaseClass->float);
        $this->assertEquals(2.2, $BaseClass->Child->float);
    }
}