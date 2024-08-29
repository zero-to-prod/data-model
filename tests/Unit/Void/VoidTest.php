<?php

namespace Tests\Unit\Void;

use Tests\TestCase;
use Zerotoprod\DataModel\DataModel;

class VoidTest extends TestCase
{
    /**
     * @test
     *
     * @see DataModel
     */
    public function void_(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::void => 1,
            BaseClass::Child => [
                Child::void => 1
            ],
        ]);

        $this->assertEquals(1, $BaseClass->void);
        $this->assertEquals(1, $BaseClass->Child->void);
    }
}