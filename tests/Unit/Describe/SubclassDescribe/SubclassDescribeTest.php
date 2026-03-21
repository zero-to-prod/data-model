<?php

namespace Tests\Unit\Describe\SubclassDescribe;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SubclassDescribeTest extends TestCase
{
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

    #[Test] public function assign_applied_with_no_context(): void
    {
        $BaseClass = BaseClass::from();

        self::assertEquals('fixed', $BaseClass->assign_prop);
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
