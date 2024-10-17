<?php

namespace Tests\Unit\Examples\AutomaticInstantiation;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AutomaticInstantiationTest extends TestCase
{

    #[Test] public function from(): void
    {
        $user = User::from([
            'name' => 'John Doe',
            'address' => [
                'street' => '123 Main St',
                'city' => 'Hometown',
            ],
        ]);

        self::assertEquals('Hometown', $user->address->city);
    }
}