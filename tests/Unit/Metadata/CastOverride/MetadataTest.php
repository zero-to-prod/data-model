<?php

namespace Tests\Unit\Metadata\CastOverride;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MetadataTest extends TestCase
{
    #[Test] public function foo(): void
    {
        define('describe_override', [
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