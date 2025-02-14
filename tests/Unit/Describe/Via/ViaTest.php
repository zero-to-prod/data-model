<?php

namespace Tests\Unit\Describe\Via;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ViaTest extends TestCase
{
    #[Test] public function from(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::ChildClass => [
                ChildClass::int => 1
            ],
            BaseClass::ChildClass2 => [
                ChildClass::int => 1
            ],
        ]);

        self::assertEquals(1, $BaseClass->ChildClass->int);
        self::assertEquals(1, $BaseClass->ChildClass2->int);
    }
}