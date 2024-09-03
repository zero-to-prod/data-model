<?php

namespace Tests\Unit\FromJson;

use InvalidArgumentException;
use Tests\TestCase;
use Zerotoprod\DataModel\DataModel;

class FromJsonTest extends TestCase
{
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

        $BaseClass = BaseClass::fromJson($json);

        $this->assertEquals(1, $BaseClass->id);
        $this->assertEquals('name', $BaseClass->name);
    }

    /**
     * @test
     *
     * @see DataModel
     */
    public function invalid_json_string(): void
    {
        $this->expectException(InvalidArgumentException::class);
        BaseClass::fromJson("'");
    }
}