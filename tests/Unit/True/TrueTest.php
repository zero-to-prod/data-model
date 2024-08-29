<?php

namespace Tests\Unit\True;

use Tests\TestCase;
use Zerotoprod\DataModel\DataModel;

class TrueTest extends TestCase
{
    /**
     * @test
     *
     * @see DataModel
     */
    public function true(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::true => true,
            BaseClass::Child => [
                Child::true => false
            ],
        ]);

        $this->assertTrue($BaseClass->true);
        $this->assertFalse($BaseClass->Child->true);
    }
}