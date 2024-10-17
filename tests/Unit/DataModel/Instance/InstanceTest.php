<?php

namespace Tests\Unit\DataModel\Instance;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class InstanceTest extends TestCase
{

    #[Test] public function creates_instance_from_array(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::id => 1,
            BaseClass::name => 'name'
        ]);

        $this->assertEquals(1, $BaseClass->id);
        $this->assertEquals('name', $BaseClass->name);
    }
}