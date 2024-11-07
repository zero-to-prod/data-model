<?php

namespace Tests\Unit\Describe\Required;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Zerotoprod\DataModel\PropertyRequiredException;

class IgnoresUnionsTest extends TestCase
{

    #[Test] public function required_with_value(): void
    {
        $this->expectException(PropertyRequiredException::class);
        BaseClass::from([
            BaseClass::required_no_value => '1',
        ]);
    }

    #[Test] public function required_without_value(): void
    {
        $this->expectException(PropertyRequiredException::class);
        BaseClass::from([
            BaseClass::required => '1',
        ]);
    }
}