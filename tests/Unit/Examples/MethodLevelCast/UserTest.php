<?php

namespace Tests\Unit\Examples\MethodLevelCast;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserTest extends TestCase
{

    #[Test] public function from(): void
    {
        $user = User::from([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
        ]);

        $this->assertEquals('Jane', $user->first_name);
        $this->assertEquals('DOE', $user->last_name);
        $this->assertEquals('Jane Doe', $user->fullName);
    }

    #[Test] public function without_value(): void
    {
        $user = User::from([
            'last_name' => 'Doe',
        ]);

        $this->assertEquals('DOE', $user->last_name);
        $this->assertEquals(' Doe', $user->fullName);
    }

    #[Test] public function without_matching_value(): void
    {
        $user = User::from([
            'first_name' => 'Jane',
        ]);

        $this->assertEquals('Jane', $user->first_name);
        $this->assertEquals('Jane ', $user->fullName);
    }
}