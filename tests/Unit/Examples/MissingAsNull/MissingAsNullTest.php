<?php

namespace Tests\Unit\Examples\MissingAsNull;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MissingAsNullTest extends TestCase
{
    #[Test] public function from(): void
    {
        $User = User::from([
            'name' => 'John',
        ]);

        $this->assertEquals('John', $User->name);
        $this->assertNull($User->age);
    }
}