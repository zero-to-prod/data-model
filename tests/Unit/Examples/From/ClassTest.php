<?php

namespace Tests\Unit\Examples\From;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ClassTest extends TestCase
{
    #[Test] public function from(): void
    {
        $User = User::from([
            'firstName' => '1',
        ]);

        $this->assertEquals('1', $User->first_name);
    }
}