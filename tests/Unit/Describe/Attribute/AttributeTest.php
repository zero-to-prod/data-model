<?php

namespace Tests\Unit\Describe\Attribute;

use Tests\TestCase;
use Zerotoprod\DataModel\Describe;
use Zerotoprod\DataModel\InvalidValue;

class AttributeTest extends TestCase
{
    public function testRequiredPositive(): void
    {
        $d = new Describe(['required' => true]);
        $this->assertTrue($d->required);
    }

    public function testRequiredNegative(): void
    {
        $this->expectException(InvalidValue::class);
        new Describe(['required' => 'yes']);
    }

    public function testNullablePositive(): void
    {
        $d = new Describe(['nullable' => false]);
        $this->assertFalse($d->nullable);
    }

    public function testNullableNegative(): void
    {
        $this->expectException(InvalidValue::class);
        new Describe(['nullable' => 'nope']);
    }

    public function testIgnorePositive(): void
    {
        $d = new Describe(['ignore' => true]);
        $this->assertTrue($d->ignore);
    }

    public function testIgnoreNegative(): void
    {
        $this->expectException(InvalidValue::class);
        new Describe(['ignore' => 1]);
    }

    public function testMissingAsNullPositive(): void
    {
        $d = new Describe([Describe::missing_as_null => true]);
        $this->assertTrue($d->nullable);
    }

    public function testMissingAsNullNegative(): void
    {
        $this->expectException(InvalidValue::class);
        new Describe([Describe::missing_as_null => 'abc']);
    }

    public function testFromPositive(): void
    {
        $d = new Describe(['from' => 'user_name']);
        $this->assertSame('user_name', $d->from);
    }

    public function testFromNegative(): void
    {
        // Should not throw error, just not assign for wrong key
        $d = new Describe(['form' => 'typo']);
        $this->assertFalse(isset($d->form));
        $this->assertNull(@$d->form);
    }

    public function testCastPositive(): void
    {
        $d = new Describe(['cast' => 'int']);
        $this->assertSame('int', $d->cast);
    }

    public function testDefaultPositive(): void
    {
        $d = new Describe(['default' => 42]);
        $this->assertSame(42, $d->default);
    }

    public function testPrePositive(): void
    {
        $d = new Describe(['pre' => 'trim']);
        $this->assertSame('trim', $d->pre);
    }

    public function testPostPositive(): void
    {
        $d = new Describe(['post' => 'strtoupper']);
        $this->assertSame('strtoupper', $d->post);
    }

    public function testViaPositive(): void
    {
        $d = new Describe(['via' => 'email']);
        $this->assertSame('email', $d->via);
    }

    public function testShortcutRequiredPositive(): void
    {
        $d = new Describe([Describe::required]);
        $this->assertTrue($d->required);
    }

    public function testShortcutNullablePositive(): void
    {
        $d = new Describe([Describe::nullable]);
        $this->assertTrue($d->nullable);
    }

    public function testShortcutIgnorePositive(): void
    {
        $d = new Describe([Describe::ignore]);
        $this->assertTrue($d->ignore);
    }

    public function testShortcutMissingAsNullPositive(): void
    {
        $d = new Describe([Describe::missing_as_null]);
        $this->assertTrue($d->nullable);
    }

    public function testShortcutWrongKeyNegative(): void
    {
        $d = new Describe([0 => 'not_a_known_alias']);
        $this->assertFalse(isset($d->not_a_known_alias));
    }

    public function testMissingAsNullAsValuePositive(): void
    {
        $d = new Describe(['yep' => Describe::missing_as_null]);
        $this->assertTrue($d->nullable);
    }

    public function testNullInput(): void
    {
        $d = new Describe();
        $this->assertInstanceOf(Describe::class, $d);
    }
}