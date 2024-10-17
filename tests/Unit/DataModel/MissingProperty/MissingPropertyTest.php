<?php

namespace Tests\Unit\DataModel\MissingProperty;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MissingPropertyTest extends TestCase
{
    #[Test] public function handles_missing_property(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::id => 1,
            'name' => 'name'
        ]);

        $this->assertEquals(1, $BaseClass->id);
        $this->assertFalse(isset($BaseClass->name));
    }
}