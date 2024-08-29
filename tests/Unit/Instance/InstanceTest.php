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

    /**
     * @test
     *
     * @see DataModel
     */
    public function creates_instance_from_json_string(): void
    {
        $json = json_encode([
            BaseClass::id => 1,
            BaseClass::name => 'name'
        ]);

        $BaseClass = BaseClass::from($json);

        $this->assertEquals(1, $BaseClass->id);
        $this->assertEquals('name', $BaseClass->name);
    }

    /**
     * @test
     *
     * @see DataModel
     */
    public function creates_instance_from_string(): void
    {
        $BaseClass = BaseClass::from('bogus');

        $this->assertTrue(is_a($BaseClass, BaseClass::class));
    }
}