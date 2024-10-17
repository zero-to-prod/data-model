<?php

namespace Tests\Unit\DataModel\FromStdClass;

use PHPUnit\Framework\Attributes\Test;
use stdClass;
use Tests\TestCase;

class FromStdClassTest extends TestCase
{
    #[Test] public function passes_object(): void
    {
        $Base = new stdClass();
        $Base->id = 1;
        $BaseClass = BaseClass::from($Base);

        $this->assertEquals(1, $BaseClass->id);
    }

    #[Test] public function passes_object_to_child(): void
    {
        $Child = new stdClass();
        $Child->id = 1;
        $BaseClass = BaseClass::from([
            BaseClass::Child => $Child,
        ]);

        $this->assertEquals(1, $BaseClass->Child->id);
    }

    #[Test] public function passes_stdClass(): void
    {
        $stdClass = new stdClass();
        $stdClass->id = 1;
        $BaseClass = BaseClass::from([
            BaseClass::stdClass => $stdClass,
        ]);

        $this->assertEquals(1, $BaseClass->stdClass->id);
    }

    #[Test] public function passes_stdClass_without_backslash(): void
    {
        $stdClass = new stdClass();
        $stdClass->id = 1;
        $BaseClass = BaseClass::from([
            BaseClass::stdClassWithoutBackslash => $stdClass,
        ]);

        $this->assertEquals(1, $BaseClass->stdClassWithoutBackslash->id);
    }
}