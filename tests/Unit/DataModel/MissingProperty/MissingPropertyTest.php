<?php

namespace Tests\Unit\DataModel\MissingProperty;

use Tests\TestCase;
use Zerotoprod\DataModel\DataModel;

class MissingPropertyTest extends TestCase
{
    /**
     * @test
     *
     * @see DataModel
     */
    public function handles_missing_property(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::id => 1,
            'name' => 'name'
        ]);

        $this->assertEquals(1, $BaseClass->id);
        $this->assertNull($BaseClass->name);
    }
}