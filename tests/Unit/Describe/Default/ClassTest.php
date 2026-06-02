<?php

namespace Tests\Unit\Describe\Default;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ClassTest extends TestCase
{
    #[Test] public function from(): void
    {
        $BaseClass = BaseClass::from();

        $this->assertEquals('1', $BaseClass->string);
    }

    #[Test] public function from_boolean(): void
    {
        $BaseClass = BaseClass::from();

        $this->assertFalse($BaseClass->bool);
    }


    #[Test] public function from_default_enum(): void
    {
        $BaseClass = BaseClass::from([BaseClass::bool => false]);

        $this->assertEquals(DefaultEnum::unknown, $BaseClass->DefaultEnum);
    }

    #[Test] public function from_reference(): void
    {
        $BaseClass = BaseClass::from();

        $this->assertInstanceOf(BaseClass::class, $BaseClass->BaseClass);
    }
}