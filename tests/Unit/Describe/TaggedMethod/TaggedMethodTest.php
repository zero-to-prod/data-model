<?php

namespace Tests\Unit\Describe\TaggedMethod;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TaggedMethodTest extends TestCase
{

    #[Test] public function taggedMethod(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::override => 'foo',
        ]);

        $this->assertEquals('bar', $BaseClass->override);
    }
}