<?php

namespace Tests\Unit\Examples\ExtendsTrait;

use Tests\TestCase;
use Zerotoprod\DataModel\DataModel;

class ClassTest extends TestCase
{
    /**
     * @test
     *
     * @see DataModel
     */
    public function from(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::string => 'foo',
        ]);

        $this->assertEquals('foo', $BaseClass->string);
    }
}