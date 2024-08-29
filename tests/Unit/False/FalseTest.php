<?php

namespace Tests\Unit\False;

use Tests\TestCase;
use Zerotoprod\DataModel\DataModel;

class FalseTest extends TestCase
{
    /**
     * @test
     *
     * @see DataModel
     */
    public function false(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::false => false,
            BaseClass::Child => [
                Child::false => false
            ],
        ]);

        $this->assertfalse($BaseClass->false);
        $this->assertFalse($BaseClass->Child->false);
    }
}