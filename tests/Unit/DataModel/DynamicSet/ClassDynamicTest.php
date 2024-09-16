<?php

namespace Tests\Unit\DataModel\DynamicSet;

use Tests\TestCase;
use Zerotoprod\DataModel\DataModel;

class ClassDynamicTest extends TestCase
{
    /**
     * @test
     *
     * @see DataModel
     */
    public function fromDynamic(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::foo => 'foo',
            BaseClass::bar => 'bar',
        ]);

        $this->assertEquals('foobar', $BaseClass->foo);
    }
}