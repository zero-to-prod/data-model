<?php

namespace Tests\Unit\BogusType;

use Tests\TestCase;
use Zerotoprod\DataModel\DataModel;

class BogusTypeTest extends TestCase
{
    /**
     * @test
     *
     * @see DataModel
     */
    public function enum(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::enum => 1,
            BaseClass::Child => [
                Child::enum => 1
            ],
        ]);

        $this->assertEquals(1, $BaseClass->enum);
        $this->assertEquals(1, $BaseClass->Child->enum);
    }
}