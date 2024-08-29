<?php

namespace Tests\Unit\Bool;

use Tests\TestCase;
use Zerotoprod\DataModel\DataModel;

class BoolTest extends TestCase
{
    /**
     * @test
     *
     * @see DataModel
     */
    public function bool(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::bool => 0,
            BaseClass::Child => [
                Child::bool => 1
            ],
        ]);

        $this->assertFalse($BaseClass->bool);
        $this->assertTrue($BaseClass->Child->bool);
    }
}