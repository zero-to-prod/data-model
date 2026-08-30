<?php

namespace Tests\Unit\Describe\Ignore;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CallableTest extends TestCase
{
    #[Test] public function skips_the_property_when_callable_returns_true(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::skip => true,
            BaseClass::value => 'new-value',
        ]);

        $this->assertEquals('untouched', $BaseClass->value);
    }

    #[Test] public function assigns_the_property_when_callable_returns_false(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::skip => false,
            BaseClass::value => 'new-value',
        ]);

        $this->assertEquals('new-value', $BaseClass->value);
    }
}
