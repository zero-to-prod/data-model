<?php

namespace Tests\Unit\Metadata\CastOverride;

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
    public function foo(): void
    {
        define('describe', [
            'cast' => [
                'string' => [Helpers::class, 'setBarString'],
            ]
        ]);
        $BaseClass = BaseClass::from([
            BaseClass::name => 'bar',
        ]);

        $this->assertEquals('foo', $BaseClass->name);
    }
}