<?php

namespace Tests\Unit\Describe\Class;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ClassTest extends TestCase
{
    #[Test] public function from(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::string => 'foo',
        ]);

        $this->assertEquals('bar', $BaseClass->string);
    }
}