<?php

namespace Tests\Unit\Describe\SubclassDescribe;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Zerotoprod\DataModel\PropertyRequiredException;

class SubclassDescribeTest extends TestCase
{
    /* ── No-context tests (from() / from('')) ── */

    #[Test] public function nullable_set_to_null_with_no_context(): void
    {
        $BaseClass = BaseClass::from();

        self::assertNull($BaseClass->nullable_prop);
    }

    #[Test] public function default_applied_with_no_context(): void
    {
        $BaseClass = BaseClass::from();

        self::assertEquals('fallback', $BaseClass->default_prop);
    }

    #[Test] public function default_callable_applied_with_no_context(): void
    {
        $BaseClass = BaseClass::from();

        self::assertEquals('callable_default', $BaseClass->default_callable_prop);
    }

    #[Test] public function assign_applied_with_no_context(): void
    {
        $BaseClass = BaseClass::from();

        self::assertEquals('fixed', $BaseClass->assign_prop);
    }

    #[Test] public function assign_callable_applied_with_no_context(): void
    {
        $BaseClass = BaseClass::from();

        self::assertEquals('callable_assign', $BaseClass->assign_callable_prop);
    }

    #[Test] public function ignored_property_not_initialized(): void
    {
        $BaseClass = BaseClass::from();

        self::assertFalse(isset($BaseClass->ignored_prop));
    }

    #[Test] public function nullable_set_to_null_with_string_context(): void
    {
        $BaseClass = BaseClass::from('');

        self::assertNull($BaseClass->nullable_prop);
    }

    #[Test] public function default_applied_with_string_context(): void
    {
        $BaseClass = BaseClass::from('');

        self::assertEquals('fallback', $BaseClass->default_prop);
    }

    #[Test] public function assign_applied_with_string_context(): void
    {
        $BaseClass = BaseClass::from('');

        self::assertEquals('fixed', $BaseClass->assign_prop);
    }

    /* ── Context-dependent tests ── */

    #[Test] public function from_remaps_key(): void
    {
        $obj = ContextClass::from([
            'remapped_key' => 'remapped_value',
            'cast_prop' => 'hello',
            'cast_four_param_prop' => 'hello',
            'pre_prop' => 'x',
            'post_prop' => 'x',
            'via_prop' => ['value' => 'v'],
        ]);

        self::assertEquals('remapped_value', $obj->from_prop);
    }

    #[Test] public function cast_single_param(): void
    {
        $obj = ContextClass::from([
            'cast_prop' => 'hello',
            'cast_four_param_prop' => 'hello',
            'pre_prop' => 'x',
            'post_prop' => 'x',
            'via_prop' => ['value' => 'v'],
        ]);

        self::assertEquals('HELLO', $obj->cast_prop);
    }

    #[Test] public function cast_four_params_with_extra(): void
    {
        $obj = ContextClass::from([
            'cast_prop' => 'hello',
            'cast_four_param_prop' => 'hello',
            'pre_prop' => 'x',
            'post_prop' => 'x',
            'via_prop' => ['value' => 'v'],
        ]);

        self::assertEquals('hello-test', $obj->cast_four_param_prop);
    }

    #[Test] public function pre_hook_fires(): void
    {
        ContextClass::$pre_called = 0;

        ContextClass::from([
            'cast_prop' => 'a',
            'cast_four_param_prop' => 'a',
            'pre_prop' => 'x',
            'post_prop' => 'x',
            'via_prop' => ['value' => 'v'],
        ]);

        self::assertEquals(1, ContextClass::$pre_called);
    }

    #[Test] public function post_hook_fires(): void
    {
        ContextClass::$post_called = 0;

        ContextClass::from([
            'cast_prop' => 'a',
            'cast_four_param_prop' => 'a',
            'pre_prop' => 'x',
            'post_prop' => 'x',
            'via_prop' => ['value' => 'v'],
        ]);

        self::assertEquals(1, ContextClass::$post_called);
    }

    #[Test] public function via_custom_instantiation(): void
    {
        $obj = ContextClass::from([
            'cast_prop' => 'a',
            'cast_four_param_prop' => 'a',
            'pre_prop' => 'x',
            'post_prop' => 'x',
            'via_prop' => ['value' => 'hello'],
        ]);

        self::assertInstanceOf(Child::class, $obj->via_prop);
        self::assertEquals('hello', $obj->via_prop->value);
    }

    #[Test] public function required_throws(): void
    {
        $this->expectException(PropertyRequiredException::class);

        RequiredClass::from([]);
    }

    #[Test] public function class_level_nullable(): void
    {
        $obj = ClassNullable::from();

        self::assertNull($obj->name);
        self::assertNull($obj->age);
    }

    #[Test] public function class_level_cast(): void
    {
        $obj = ClassLevelCast::from(['name' => 'hello']);

        self::assertEquals('HELLO', $obj->name);
    }

    #[Test] public function method_level_describe(): void
    {
        $obj = MethodLevelClass::from(['name' => 'hello']);

        self::assertEquals('HELLO', $obj->name);
    }

    #[Test] public function context_values_override_defaults(): void
    {
        $BaseClass = BaseClass::from([
            'nullable_prop' => 'present',
            'default_prop' => 'override',
        ]);

        self::assertEquals('present', $BaseClass->nullable_prop);
        self::assertEquals('override', $BaseClass->default_prop);
        self::assertEquals('fixed', $BaseClass->assign_prop);
    }
}
