<?php

namespace Tests\Unit\DataModel\FromDataModel;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FromDataModelTest extends TestCase
{

    #[Test] public function from_data_model(): void
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

    #[Test] public function from_child_data_model(): void
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