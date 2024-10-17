<?php

namespace Tests\Unit\DataModel\DynamicSet;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ClassDynamicTest extends TestCase
{
    #[Test] public function fromDynamic(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::foo => 'foo',
            BaseClass::bar => 'bar',
        ]);

        $this->assertEquals('foobar', $BaseClass->foo);
    }
}