<?php

namespace Tests\Unit\FromDataModel;

use Tests\TestCase;
use Zerotoprod\DataModel\DataModel;

class FromDataModelTest extends TestCase
{
    /**
     * @test
     *
     * @see DataModel
     */
    public function from_data_model(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::id => 1,
            BaseClass::Child => Child::from([
                Child::id => 1,
            ]),
        ]);

        $BaseClass = BaseClass::from($BaseClass);

        $this->assertEquals(1, $BaseClass->id);
        $this->assertEquals(1, $BaseClass->Child->id);
    }

    /**
     * @test
     *
     * @see DataModel
     */
    public function from_child_data_model(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::id => 1,
            BaseClass::Child => Child::from([
                Child::id => 1,
            ]),
        ]);

        $this->assertEquals(1, $BaseClass->id);
        $this->assertEquals(1, $BaseClass->Child->id);
    }
}