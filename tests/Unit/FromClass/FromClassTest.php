<?php

namespace Tests\Unit\FromClass;

use Tests\TestCase;
use Zerotoprod\DataModel\DataModel;

class FromClassTest extends TestCase
{
    /**
     * @test
     *
     * @see DataModel
     */
    public function calls_from_method(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::Child => 1,
        ]);

        $this->assertEquals(1, $BaseClass->Child->id);
    }

    /**
     * @test
     *
     * @see DataModel
     */
    public function does_not_assign_without_from_method(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::ChildWithoutFrom => [
                ChildWithoutFrom::id => 1
            ],
        ]);

        $this->assertNull($BaseClass->ChildWithoutFrom->id);
    }
}