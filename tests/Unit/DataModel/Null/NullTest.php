<?php

namespace Tests\Unit\DataModel\Null;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NullTest extends TestCase
{
    #[Test] public function bool(): void
    {
        $BaseClass = BaseClass::from(null);

        $this->assertInstanceOf(BaseClass::class, $BaseClass);
    }
}