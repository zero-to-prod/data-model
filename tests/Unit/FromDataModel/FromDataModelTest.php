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
            BaseClass::Child => Child::from([
                Child::id => 1,
            ]),
        ]);

        $this->assertEquals(1, $BaseClass->Child->id);
    }
}