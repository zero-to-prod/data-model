<?php

namespace Tests\Unit\Examples\ClassLevelCast;

use DateTimeImmutable;
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
            'registered' => '2015-10-04 17:24:43.000000',
        ]);

        $this->assertEquals('JANE', $user->first_name);
        $this->assertEquals('Sunday', $user->registered->format('l'));
    }
}