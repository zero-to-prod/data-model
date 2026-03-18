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
}
