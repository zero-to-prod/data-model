<?php

namespace Tests\Unit\Instance;

use Tests\TestCase;
use Zerotoprod\DataModel\DataModel;

class InstanceTest extends TestCase
{
    /**
     * @test
     *
     * @see DataModel
     */
    public function creates_instance_from_array(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::id => 1,
            BaseClass::name => 'name'
        ]);

        $this->assertEquals(1, $BaseClass->id);
        $this->assertEquals('name', $BaseClass->name);
    }
}