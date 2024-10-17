<?php

namespace Tests\Unit\DataModel\FromArray;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ArrayTest extends TestCase
{
    #[Test] public function array(): void
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