<?php

namespace Tests\Unit\DataModel\FromArray;

use stdClass;
use Tests\TestCase;
use Zerotoprod\DataModel\DataModel;

class ArrayTest extends TestCase
{
    /**
     * @test
     *
     * @see DataModel
     */
    public function array(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::array => [1],
            BaseClass::Child => [
                Child::array => ['2']
            ],
            BaseClass::object => ['foo' => 'bar']
        ]);

        $this->assertEquals([1], $BaseClass->array);
        $this->assertEquals(['2'], $BaseClass->Child->array);
        $this->assertEquals(['foo' => 'bar'], $BaseClass->object);
    }
}