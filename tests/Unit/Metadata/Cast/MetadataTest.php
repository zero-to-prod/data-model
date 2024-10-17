<?php

namespace Tests\Unit\Metadata\Cast;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MetadataTest extends TestCase
{
    #[Test] public function from(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::DateTimeImmutable => '2015-10-04 17:24:43.000000',
            BaseClass::name => 'foo',
        ]);

        $this->assertInstanceOf(DateTimeImmutable::class, $BaseClass->DateTimeImmutable);
        $this->assertEquals('bar', $BaseClass->name);
    }
}