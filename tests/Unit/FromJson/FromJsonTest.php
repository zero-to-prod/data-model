<?php

namespace Tests\Unit\FromJson;

use Tests\TestCase;
use Zerotoprod\DataModel\FromJson;

class FromJsonTest extends TestCase
{
    /**
     * @test
     *
     * @see FromJson::fromJson()
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
     * @see FromJson::tryFromJson()
     */
    public function invalid_json_string(): void
    {
        $this->assertNull(BaseClass::tryFromJson("'"));
    }
}