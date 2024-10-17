<?php

namespace Tests\Unit\Examples\PropertyLevelCast;

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

        $this->assertEquals('JANE', $user->first_name);
        $this->assertEquals('DOE', $user->last_name);
        $this->assertEquals('Jane Doe', $user->full_name);
    }
}