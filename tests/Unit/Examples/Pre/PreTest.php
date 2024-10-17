<?php

namespace Tests\Unit\Examples\Pre;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class
PreTest extends TestCase
{
    #[Test] public function from(): void
    {
        $this->expectExceptionMessage('Value too large.');

        BaseClass::from([
            BaseClass::int => 100,
        ]);
    }

    #[Test] public function fails_check(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::int => 1,
        ]);

        self::assertEquals(1, $BaseClass->int);
    }
}