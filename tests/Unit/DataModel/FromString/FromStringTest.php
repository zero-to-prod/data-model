<?php

namespace Tests\Unit\DataModel\FromString;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FromStringTest extends TestCase
{
    #[Test] public function nullable_set_to_null(): void
    {
        $BaseClass = BaseClass::from('context');

        $this->assertNull($BaseClass->nullable_prop);
    }

    #[Test] public function default_applied(): void
    {
        $BaseClass = BaseClass::from('context');

        $this->assertEquals('fallback', $BaseClass->default_prop);
    }

    #[Test] public function assign_applied(): void
    {
        $BaseClass = BaseClass::from('context');

        $this->assertEquals('fixed', $BaseClass->assign_prop);
    }

    #[Test] public function empty_string_nullable(): void
    {
        $BaseClass = BaseClass::from('');

        $this->assertNull($BaseClass->nullable_prop);
    }

    #[Test] public function empty_string_default(): void
    {
        $BaseClass = BaseClass::from('');

        $this->assertEquals('fallback', $BaseClass->default_prop);
    }

    #[Test] public function empty_string_assign(): void
    {
        $BaseClass = BaseClass::from('');

        $this->assertEquals('fixed', $BaseClass->assign_prop);
    }
}
