<?php

namespace Tests\Unit\Examples\Post;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class
PostTest extends TestCase
{
    #[Test] public function from(): void
    {
        $this->expectExceptionMessage('Value too large.');

        BaseClass::from([
            BaseClass::int => 100,
        ]);
    }

    #[Test] public function does_not_throw_exception(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::int => 1,
        ]);

        self::assertEquals(1, $BaseClass->int);
    }
}