<?php

namespace Tests\Unit\Examples\MethodLevelCast;

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
            'first_name' => 'Jane',
            'last_name' => 'Doe',
        ]);

        $this->assertEquals('Jane', $user->first_name);
        $this->assertEquals('DOE', $user->last_name);
        $this->assertEquals('Jane Doe', $user->fullName);
    }

    /**
     * @test
     *
     * @see DataModel
     */
    public function without_value(): void
    {
        $user = User::from([
            'last_name' => 'Doe',
        ]);

        $this->assertEquals('DOE', $user->last_name);
        $this->assertEquals(' Doe', $user->fullName);
    }

    /**
     * @test
     *
     * @see DataModel
     */
    public function without_matching_value(): void
    {
        $user = User::from([
            'first_name' => 'Jane',
        ]);

        $this->assertEquals('Jane', $user->first_name);
        $this->assertEquals('Jane ', $user->fullName);
    }
}