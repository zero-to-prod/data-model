<?php

namespace Tests\Unit\DataModel\String;

use Tests\TestCase;
use Zerotoprod\DataModel\DataModel;

class StringTest extends TestCase
{
    /**
     * @test
     *
     * @see DataModel
     */
    public function string(): void
    {
        $BaseClass = BaseClass::from([
            BaseClass::string => 1,
            BaseClass::Child => [
                Child::string => 1.1
            ],
        ]);

        $this->assertEquals('1', $BaseClass->string);
        $this->assertEquals('1.1', $BaseClass->Child->string);
    }
}