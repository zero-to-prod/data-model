<?php

namespace Tests\Unit\Describe\Class;

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

        $this->assertEquals('bar', $BaseClass->string);
    }
}