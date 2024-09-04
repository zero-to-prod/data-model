<?php

namespace Tests\Unit\DataModel\Recursion;

use stdClass;
use Tests\TestCase;
use Zerotoprod\DataModel\DataModel;

class InstanceRecursionTest extends TestCase
{
    /**
     * @test
     *
     * @see DataModel
     */
    public function recursively_creates_instance_from_array(): void
    {
        $object = new stdClass;
        $object->id = 1;
        $BaseClass = BaseClass::from([
            BaseClass::id => 1,
            BaseClass::name => 'name',
            BaseClass::price => 1.00,
            BaseClass::is_free => true,
            BaseClass::list => [1, 2],
            BaseClass::object => $object,
            BaseClass::stdClass => $object,
            BaseClass::mixed => 'mixed',
            BaseClass::Child => [
                Child::id => 1,
                Child::name => 'name',
            ],
            BaseClass::ShortNamespaceChild => [
                ShortNamespaceChild::id => 1,
                ShortNamespaceChild::name => 'name',
            ]
        ]);

        $this->assertEquals(1, $BaseClass->id);
        $this->assertEquals('name', $BaseClass->name);
        $this->assertEquals(1.00, $BaseClass->price);
        $this->assertTrue($BaseClass->is_free);
        $this->assertEquals([1, 2], $BaseClass->list);
        $this->assertEquals($object, $BaseClass->object);
        $this->assertEquals($object, $BaseClass->stdClass);
        $this->assertEquals('mixed', $BaseClass->mixed);
        $this->assertEquals(1, $BaseClass->Child->id);
        $this->assertEquals('name', $BaseClass->Child->name);
        $this->assertEquals(1, $BaseClass->ShortNamespaceChild->id);
        $this->assertEquals('name', $BaseClass->ShortNamespaceChild->name);
    }
}