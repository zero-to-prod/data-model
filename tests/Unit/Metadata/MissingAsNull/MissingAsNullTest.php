<?php

namespace Tests\Unit\Metadata\MissingAsNull;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MissingAsNullTest extends TestCase
{
    #[Test] public function from(): void
    {
        $User = User::from();

        $this->assertNull($User->name);
        $this->assertNull($User->age);
    }
}