<?php

namespace Tests\Unit\Describe\Nullable\Invalid;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Zerotoprod\DataModel\InvalidValue;

class ClassTest extends TestCase
{
    #[Test] public function from(): void
    {
        $this->expectException(InvalidValue::class);
        BaseClass::from();
    }
}