<?php

namespace Tests\Unit\Examples\ExtendsTrait;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ClassTest extends TestCase
{
    #[Test] public function from(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::string => 'foo',
        ]);

        $this->assertEquals('foo', $BaseClass->string);
    }
}