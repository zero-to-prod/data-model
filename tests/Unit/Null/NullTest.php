<?php

namespace Tests\Unit\Null;

use Tests\TestCase;
use Zerotoprod\DataModel\DataModel;

class NullTest extends TestCase
{
    /**
     * @test
     *
     * @see DataModel
     */
    public function null(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::null => null,
            BaseClass::Child => [
                Child::null => null
            ],
        ]);

        $this->assertNull($BaseClass->null);
        $this->assertNull($BaseClass->Child->null);
    }
}