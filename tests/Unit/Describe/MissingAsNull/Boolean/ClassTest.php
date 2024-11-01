<?php

namespace Tests\Unit\Describe\MissingAsNull\Boolean;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ClassTest extends TestCase
{
    #[Test] public function from(): void
    {
        $BaseClass = BaseClass::from();

        self::assertNull($BaseClass->true);
        self::assertFalse(isset($BaseClass->false));
    }
}