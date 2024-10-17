<?php

namespace Tests\Unit\Examples\Usage;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserTest extends TestCase
{
    #[Test] public function from(): void
    {
        $user = User::from([
            'name' => 'John Doe',
            'age' => '30',
        ]);

        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals(30, $user->age);
    }
}