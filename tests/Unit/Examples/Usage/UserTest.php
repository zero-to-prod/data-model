<?php

namespace Tests\Unit\Examples\Usage;

use Tests\TestCase;
use Zerotoprod\DataModel\DataModel;

class UserTest extends TestCase
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
            'age' => '30',
        ]);

        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals(30, $user->age);
    }
}