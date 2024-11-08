<?php

namespace Tests\Unit\Describe\HandlesArray;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class IsUrlTest extends TestCase
{
    #[Test] public function valid_url(): void
    {
        $User = User::from([
            User::url => 'https://example.com/',
        ]);

        self::assertEquals('https://example.com/', $User->url);
    }
}