<?php

namespace Tests\Unit\Describe\ClassDynamic;

use DateTime;
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
            BaseClass::string => 'foo',
            BaseClass::string2 => 'foo',
            BaseClass::string3 => 'foo',
            BaseClass::string4 => 'foo',
            BaseClass::string5 => 'foo',
            BaseClass::DateTime => '2015-10-04 17:24:43.000000',
            'first_name' => 'john',
            'last_name' => 'doe',
        ]);

        $this->assertEquals('foobar', $BaseClass->string);
        $this->assertEquals('bar', $BaseClass->string2);
        $this->assertEquals('foo', $BaseClass->string3);
        $this->assertEquals('bar', $BaseClass->string4);
        $this->assertEquals('bar', $BaseClass->string5);
        $this->assertInstanceOf(DateTime::class, $BaseClass->DateTime);
        $this->assertEquals('john doe', $BaseClass->full_name);
    }
}