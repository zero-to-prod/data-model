<?php

namespace Tests\Unit\DataModel\String;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StringTest extends TestCase
{
    #[Test] public function string(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::string => 1,
            BaseClass::Child => [
                Child::string => 1.1
            ],
        ]);

        $this->assertEquals('1', $BaseClass->string);
        $this->assertEquals('1.1', $BaseClass->Child->string);
    }

    #[Test] public function passes_string(): void
    {
        $BaseClass = BaseClass::from('user');

        $this->assertInstanceOf(BaseClass::class, $BaseClass);
    }
}