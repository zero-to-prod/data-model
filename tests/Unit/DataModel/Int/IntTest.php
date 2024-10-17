<?php

namespace Tests\Unit\DataModel\Int;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class IntTest extends TestCase
{

    #[Test] public function int(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::int => '1',
            BaseClass::Child => [
                Child::int => '1'
            ],
        ]);

        $this->assertEquals(1, $BaseClass->int);
        $this->assertEquals(1, $BaseClass->Child->int);
    }
}