<?php

namespace Tests\Unit\Examples\Assign;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AssignTest extends TestCase
{
    #[Test] public function assigns_when_key_absent(): void
    {
        $user = User::from();

        $this->assertEquals(['role' => 'admin'], $user->config);
    }

    #[Test] public function assigns_when_key_present(): void
    {
        $user = User::from(['config' => ['role' => 'guest']]);

        $this->assertEquals(['role' => 'admin'], $user->config);
    }

    #[Test] public function delegates_to_callable(): void
    {
        $user = User::from();

        $this->assertEquals('service-account', $user->account);
    }

    #[Test] public function callable_ignores_context_value(): void
    {
        $user = User::from(['account' => 'other']);

        $this->assertEquals('service-account', $user->account);
    }

    #[Test] public function delegates_to_callable_returning_array(): void
    {
        $user = User::from(['permissions' => ['none']]);

        $this->assertEquals(['read', 'write'], $user->permissions);
    }
}
