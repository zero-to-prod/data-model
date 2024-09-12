<?php

namespace Tests\Unit\Describe\From;

use Tests\TestCase;
use Zerotoprod\DataModel\DataModel;

class FromTest extends TestCase
{
    /**
     * @test
     *
     * @see DataModel
     */
    public function bool(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::string => 'foo',
        ]);

        $this->assertEquals('bar', $BaseClass->string);
    }
}