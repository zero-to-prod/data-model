<?php

namespace Tests\Unit\Examples\AutomaticInstantiation;

use Tests\TestCase;
use Zerotoprod\DataModel\DataModel;

class AutomaticInstantiationTest extends TestCase
{
    /**
     * @test
     *
     * @see DataModel
     */
    public function from(): void
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