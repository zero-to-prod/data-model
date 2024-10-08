<?php

namespace Tests\Unit\DataModel\Int;

use Tests\TestCase;
use Zerotoprod\DataModel\DataModel;

class IntTest extends TestCase
{
    /**
     * @test
     *
     * @see DataModel
     */
    public function int(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::int => '1',
            BaseClass::Child => [
                Child::int => '1'
            ],
        ]);

        $this->assertEquals(1, $BaseClass->int);
        $this->assertEquals(1, $BaseClass->Child->int);
    }
}