<?php

namespace Tests\Unit\Examples\Default;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DefaultTest extends TestCase
{
    #[Test] public function from(): void
    {
        $user = User::from();

        $this->assertEquals('James', $user->name);
    }
}