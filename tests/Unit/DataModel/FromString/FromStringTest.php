<?php

namespace Tests\Unit\DataModel\FromString;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Zerotoprod\DataModel\PropertyRequiredException;

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

    #[Test] public function default_callable_applied(): void
    {
        $BaseClass = BaseClass::from('context');

        $this->assertEquals('callable_default', $BaseClass->default_callable_prop);
    }

    #[Test] public function assign_applied(): void
    {
        $BaseClass = BaseClass::from('context');

        $this->assertEquals('fixed', $BaseClass->assign_prop);
    }

    #[Test] public function assign_callable_applied(): void
    {
        $BaseClass = BaseClass::from('context');

        $this->assertEquals('callable_assign', $BaseClass->assign_callable_prop);
    }

    #[Test] public function ignore_skips_property(): void
    {
        $BaseClass = BaseClass::from('context');

        $this->assertFalse(isset($BaseClass->ignored_prop));
    }

    #[Test] public function required_throws(): void
    {
        $this->expectException(PropertyRequiredException::class);

        RequiredClass::from('context');
    }

    #[Test] public function pre_hook_fires(): void
    {
        HookClass::$pre_called = 0;

        HookClass::from('context');

        $this->assertEquals(1, HookClass::$pre_called);
    }

    #[Test] public function post_hook_fires(): void
    {
        HookClass::$post_called = 0;

        HookClass::from('context');

        $this->assertEquals(1, HookClass::$post_called);
    }

    #[Test] public function class_level_nullable(): void
    {
        $ClassNullable = ClassNullable::from('context');

        $this->assertNull($ClassNullable->name);
        $this->assertNull($ClassNullable->age);
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
