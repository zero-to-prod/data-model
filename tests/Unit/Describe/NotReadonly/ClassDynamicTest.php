<?php

namespace Tests\Unit\Describe\NotReadonly;

use DateTime;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ClassDynamicTest extends TestCase
{
    #[Test] public function fromDynamic(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::string_from_class => 'foo',
            BaseClass::string_from_function => 'foo',
            BaseClass::DateTime => '2015-10-04 17:24:43.000000',
            'first_name' => 'John',
            'last_name' => 'Doe',
            BaseClass::no_type => 'foo',
        ]);

        $this->assertEquals('bar', $BaseClass->string_from_class);
        $this->assertEquals('foobar', $BaseClass->string_from_function);
        $this->assertEquals('John Doe', $BaseClass->fullName);
        $this->assertInstanceOf(DateTime::class, $BaseClass->DateTime);
        $this->assertEquals('foo', $BaseClass->no_type);
    }
}