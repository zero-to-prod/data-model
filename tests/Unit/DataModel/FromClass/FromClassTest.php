<?php

namespace Tests\Unit\DataModel\FromClass;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use TypeError;

class FromClassTest extends TestCase
{
    #[Test] public function calls_from_method(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::Child => 1,
        ]);

        $this->assertEquals(1, $BaseClass->Child->id);
    }

    #[Test] public function does_not_assign_without_from_method(): void
    {
        $this->expectException(TypeError::class);
        BaseClass::from([
            BaseClass::ChildWithoutFrom => [
                ChildWithoutFrom::id => 1
            ],
        ]);
    }
}