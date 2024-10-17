<?php

namespace Tests\Unit\Metadata\CastFromConst;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MetadataTest extends TestCase
{

    #[Test] public function from(): void
    {
        define('describe', [
            'cast' => [
                DateTimeImmutable::class => [Helpers::class, 'dateTimeImmutable'],
                'string' => [Helpers::class, 'setString'],
            ]
        ]);
        $BaseClass = BaseClass::from([
            BaseClass::DateTimeImmutable => '2015-10-04 17:24:43.000000',
            BaseClass::name => 'foo',
        ]);

        $this->assertInstanceOf(DateTimeImmutable::class, $BaseClass->DateTimeImmutable);
        $this->assertEquals('bar', $BaseClass->name);
    }
}