<?php

namespace Tests\Unit\Metadata\Cast;

use DateTimeImmutable;
use Tests\TestCase;
use Zerotoprod\DataModel\DataModel;

class MetadataTest extends TestCase
{
    /**
     * @test
     *
     * @see DataModel
     */
    public function from(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::DateTimeImmutable => '2015-10-04 17:24:43.000000',
            BaseClass::name => 'foo',
        ]);

        $this->assertInstanceOf(DateTimeImmutable::class, $BaseClass->DateTimeImmutable);
        $this->assertEquals('bar', $BaseClass->name);
    }
}