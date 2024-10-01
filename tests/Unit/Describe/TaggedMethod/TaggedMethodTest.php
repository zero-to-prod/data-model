<?php

namespace Tests\Unit\Describe\TaggedMethod;

use Tests\TestCase;
use Zerotoprod\DataModel\DataModel;

class TaggedMethodTest extends TestCase
{
    /**
     * @test
     *
     * @see DataModel
     */
    public function taggedMethod(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::override => 'foo',
        ]);

        $this->assertEquals('bar', $BaseClass->override);
    }
}