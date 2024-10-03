<?php

namespace Tests\Unit\DataModel\Bool;

use Tests\TestCase;
use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\PropertyRequiredException;

class BoolTest extends TestCase
{
    /**
     * @test
     *
     * @see DataModel
     */
    public function bool(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::bool => 0,
            BaseClass::bool_describe => '1',
            BaseClass::bool_required => true,
            BaseClass::Child => [
                Child::bool => 1
            ],
        ]);

        $this->assertFalse($BaseClass->bool);
        $this->assertTrue($BaseClass->bool_describe);
        $this->assertTrue($BaseClass->Child->bool);
    }

    /**
     * @test
     *
     * @see DataModel
     */
    public function bool_required(): void
    {
        $this->expectException(PropertyRequiredException::class);
        BaseClass::from(['bogus' => 'bogus']);
    }
}