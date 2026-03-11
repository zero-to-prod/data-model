<?php

namespace Tests\Unit\Describe\CastClosure;

use Closure;
use PHPUnit\Framework\Attributes\Test;
use ReflectionFunction;
use Tests\TestCase;
use Zerotoprod\DataModel\Describe;

class CastClosureTest extends TestCase
{
    #[Test]
    public function cast_with_single_argument_closure(): void
    {
        $describe = new Describe(['cast' => fn($value) => strtolower($value)]);

        $this->assertInstanceOf(Closure::class, $describe->cast);
        $this->assertEquals(1, (new ReflectionFunction($describe->cast))->getNumberOfParameters());
        $this->assertEquals('hello', ($describe->cast)('HELLO'));
    }

    #[Test]
    public function cast_with_four_argument_closure(): void
    {
        $describe = new Describe(['cast' => fn($value, $context, $attribute, $property) => strtolower($value)]);

        $this->assertInstanceOf(Closure::class, $describe->cast);
        $this->assertEquals(4, (new ReflectionFunction($describe->cast))->getNumberOfParameters());
        $this->assertEquals('hello', ($describe->cast)('HELLO', [], null, null));
    }
}
