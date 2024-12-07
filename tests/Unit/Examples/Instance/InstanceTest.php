<?php

namespace Tests\Unit\Examples\Instance;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class InstanceTest extends TestCase
{

    #[Test] public function creates_instance_from_array(): void
    {
        $BaseClass = new User([
           'name' => 'name'
        ]);

        $this->assertEquals('name', $BaseClass->name);
    }
}