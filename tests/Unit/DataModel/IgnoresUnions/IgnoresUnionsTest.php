<?php

namespace Tests\Unit\DataModel\IgnoresUnions;

use Tests\TestCase;
use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\PropertyRequired;

class IgnoresUnionsTest extends TestCase
{
    /**
     * @test
     *
     * @see DataModel
     */
    public function ignores_unions(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::id => 1,
            BaseClass::required => '1',
            BaseClass::no_type => '1',
        ]);

        $this->assertEquals(1, $BaseClass->id);
        $this->assertEquals('1', $BaseClass->required);

        $BaseClass = BaseClass::from([
            BaseClass::id => '1',
            BaseClass::required => '1',
            BaseClass::no_type => '1',
        ]);

        $this->assertEquals('1', $BaseClass->id);
        $this->assertEquals('1', $BaseClass->required);
        $this->assertEquals('1', $BaseClass->no_type);
    }

    /**
     * @test
     *
     * @see DataModel
     */
    public function requires_unions(): void
    {
        $this->expectException(PropertyRequired::class);
        BaseClass::from([
            BaseClass::id => '1',
            BaseClass::no_type => '1',
        ]);
    }

    /**
     * @test
     *
     * @see DataModel
     */
    public function requires_no_type(): void
    {
        $this->expectException(PropertyRequired::class);
        BaseClass::from([
            BaseClass::id => '1',
            BaseClass::required => '1',
        ]);
    }
}