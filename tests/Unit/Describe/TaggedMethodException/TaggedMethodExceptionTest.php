<?php

namespace Tests\Unit\Describe\TaggedMethodException;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Zerotoprod\DataModel\DuplicateDescribeAttributeException;

class TaggedMethodExceptionTest extends TestCase
{

    #[Test] public function taggedMethodException(): void
    {
        $this->expectException(DuplicateDescribeAttributeException::class);

        BaseClass::from([
            BaseClass::override => 'foo',
        ]);
    }
}