<?php

namespace Tests\Unit\Describe\DefaultPost;

use PHPUnit\Framework\Attributes\Test;
use RuntimeException;
use Tests\TestCase;

class DefaultTest extends TestCase
{
    #[Test] public function from(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('James');
        User::from();
    }

    #[Test] public function does_not_override(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('John');
        User::from([
            'name' => 'John'
        ]);
    }
}