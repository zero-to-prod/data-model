<?php

namespace Tests\Unit\Describe\ClassLevelCastReadonly;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ClaimSyncTest extends TestCase
{
    #[Test] public function class_level_cast_applies_to_readonly_class(): void
    {
        $ClaimSync = ClaimSync::from([
            ClaimSync::name => 'HELLO World',
        ]);

        $this->assertEquals('hello world', $ClaimSync->name);
    }
}