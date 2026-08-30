<?php

namespace Tests\Unit\Describe\Required\Callable;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Zerotoprod\DataModel\PropertyRequiredException;

class CallableTest extends TestCase
{
    #[Test] public function throws_when_callable_returns_true(): void
    {
        $this->expectException(PropertyRequiredException::class);

        BaseClass::from([
            BaseClass::flag => true,
        ]);
    }

    #[Test] public function does_not_throw_when_callable_returns_false(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::flag => false,
        ]);

        $this->assertFalse(isset($BaseClass->required));
    }
}
