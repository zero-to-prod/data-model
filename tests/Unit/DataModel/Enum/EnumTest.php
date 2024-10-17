<?php

namespace Tests\Unit\DataModel\Enum;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EnumTest extends TestCase
{
    #[Test] public function string(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::string => 1,
            BaseClass::StringEnum => StringEnum::string,
            BaseClass::enum_value => 'string',
            BaseClass::IntEnum => 1,
        ]);

        $this->assertEquals('string', $BaseClass->StringEnum->value);
        $this->assertEquals('string', $BaseClass->enum_value->value);
        $this->assertInstanceOf(StringEnum::class, $BaseClass->StringEnum);
        $this->assertEquals(1, $BaseClass->IntEnum->value);
        $this->assertInstanceOf(IntEnum::class, $BaseClass->IntEnum);
    }
}