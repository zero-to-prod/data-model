<?php

namespace Tests\Unit\Examples\Ignore;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class IgnoreTest extends TestCase
{
    #[Test] public function from(): void
    {
        $user = User::from([
            'name' => 'John Doe',
            'age' => '30',
        ]);

        $this->assertEquals('John Doe', $user->name);
        $this->assertFalse(isset($user->age));
    }
}