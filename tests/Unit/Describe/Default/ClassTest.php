<?php

namespace Tests\Unit\Describe\Default;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ClassTest extends TestCase
{
    #[Test] public function from(): void
    {
        $BaseClass = BaseClass::from();

        $this->assertEquals('1', $BaseClass->string);
    }
}