<?php

namespace Tests\Unit\Describe\From;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CallableTest extends TestCase
{
    #[Test] public function reads_the_preferred_key_when_present(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::first_name => 'Jane',
            BaseClass::legacy_name => 'Legacy Jane',
        ]);

        $this->assertEquals('Jane', $BaseClass->name);
    }

    #[Test] public function falls_back_to_the_legacy_key_when_preferred_is_absent(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::legacy_name => 'Legacy Jane',
        ]);

        $this->assertEquals('Legacy Jane', $BaseClass->name);
    }
}
