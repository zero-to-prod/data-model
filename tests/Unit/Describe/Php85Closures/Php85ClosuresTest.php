<?php

namespace Tests\Unit\Describe\Php85Closures;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Zerotoprod\DataModel\PropertyRequiredException;

/**
 * Verifies that `from`, `cast`, `required`, `default`, `pre`, `post`, `ignore`, and `assign`
 * all accept a closure literal written directly inside a `#[Describe([...])]` attribute,
 * which is only possible on PHP 8.5+ (closures in constant expressions).
 */
class Php85ClosuresTest extends TestCase
{
    #[Test] public function from_closure_remaps_the_context_key(): void
    {
        $BaseClass = BaseClass::from([
            'renamed_key' => 'value-from-renamed-key',
            'hook_prop' => 'x',
            BaseClass::cast_prop => 'x',
            BaseClass::required_prop => 'x',
            BaseClass::assign_prop => 'ignored',
        ]);

        $this->assertEquals('value-from-renamed-key', $BaseClass->from_prop);
    }

    #[Test] public function cast_closure_transforms_the_value(): void
    {
        $BaseClass = BaseClass::from([
            'renamed_key' => 'x',
            'hook_prop' => 'x',
            BaseClass::cast_prop => 'shout',
            BaseClass::required_prop => 'x',
            BaseClass::assign_prop => 'ignored',
        ]);

        $this->assertEquals('SHOUT', $BaseClass->cast_prop);
    }

    #[Test] public function required_closure_throws_when_key_absent(): void
    {
        $this->expectException(PropertyRequiredException::class);

        BaseClass::from([
            'renamed_key' => 'x',
            'hook_prop' => 'x',
            BaseClass::cast_prop => 'x',
            BaseClass::assign_prop => 'ignored',
        ]);
    }

    #[Test] public function required_closure_does_not_throw_when_key_present(): void
    {
        $BaseClass = BaseClass::from([
            'renamed_key' => 'x',
            'hook_prop' => 'x',
            BaseClass::cast_prop => 'x',
            BaseClass::required_prop => 'present',
            BaseClass::assign_prop => 'ignored',
        ]);

        $this->assertEquals('present', $BaseClass->required_prop);
    }

    #[Test] public function default_closure_supplies_the_value_when_key_absent(): void
    {
        $BaseClass = BaseClass::from([
            'renamed_key' => 'x',
            'hook_prop' => 'x',
            BaseClass::cast_prop => 'x',
            BaseClass::required_prop => 'x',
            BaseClass::assign_prop => 'ignored',
        ]);

        $this->assertEquals(['a', 'b', 'c'], $BaseClass->default_prop);
    }

    #[Test] public function pre_and_post_closures_are_invoked(): void
    {
        BaseClass::$pre_calls = [];
        BaseClass::$post_calls = [];

        BaseClass::from([
            'renamed_key' => 'x',
            BaseClass::cast_prop => 'x',
            BaseClass::required_prop => 'x',
            'hook_prop' => 'hook-value',
            BaseClass::assign_prop => 'ignored',
        ]);

        $this->assertEquals(['hook-value'], BaseClass::$pre_calls);
        $this->assertEquals(['hook-value'], BaseClass::$post_calls);
    }

    #[Test] public function ignore_closure_skips_the_property(): void
    {
        $BaseClass = BaseClass::from([
            'renamed_key' => 'x',
            'hook_prop' => 'x',
            BaseClass::cast_prop => 'x',
            BaseClass::required_prop => 'x',
            BaseClass::ignore_prop => 'should-not-be-set',
            BaseClass::assign_prop => 'ignored',
        ]);

        $this->assertEquals('untouched', $BaseClass->ignore_prop);
    }

    #[Test] public function assign_closure_always_sets_the_value(): void
    {
        $BaseClass = BaseClass::from([
            'renamed_key' => 'x',
            'hook_prop' => 'x',
            BaseClass::cast_prop => 'x',
            BaseClass::required_prop => 'x',
            BaseClass::assign_prop => 'context-value-ignored',
        ]);

        $this->assertEquals('assigned-value', $BaseClass->assign_prop);
    }
}
