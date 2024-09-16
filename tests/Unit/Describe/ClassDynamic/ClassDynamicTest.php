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
            BaseClass::string_from_class => 'foo',
            BaseClass::string_from_function => 'foo',
            BaseClass::DateTime => '2015-10-04 17:24:43.000000',
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $this->assertEquals('bar', $BaseClass->string_from_class);
        $this->assertEquals('foobar', $BaseClass->string_from_function);
        $this->assertEquals('John Doe', $BaseClass->fullName);
        $this->assertInstanceOf(DateTime::class, $BaseClass->DateTime);
    }
}