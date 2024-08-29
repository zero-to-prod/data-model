<?php

namespace Tests\Unit\Never;

use Tests\TestCase;
use Zerotoprod\DataModel\DataModel;

class NeverTest extends TestCase
{
    /**
     * @test
     *
     * @see DataModel
     */
    public function never_(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::never => 1,
            BaseClass::Child => [
                Child::never => 1
            ],
        ]);

        $this->assertEquals(1, $BaseClass->never);
        $this->assertEquals(1, $BaseClass->Child->never);
    }
}