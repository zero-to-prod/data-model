<?php

namespace Tests\Unit\Describe\TaggedMethodException;

use Tests\TestCase;
use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\DuplicateDescribeAttributeException;

class TaggedMethodExceptionTest extends TestCase
{
    /**
     * @test
     *
     * @see DataModel
     */
    public function taggedMethodException(): void
    {
        $this->expectException(DuplicateDescribeAttributeException::class);

        BaseClass::from([
            BaseClass::override => 'foo',
        ]);
    }
}