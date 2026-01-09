<?php

namespace Tests\Unit\Describe\CastSingleArg;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CastSingleArgTest extends TestCase
{
    #[Test]
    public function cast_with_single_argument_function(): void
    {
        $model = CastSingleArgModel::from([
            'name' => 'HELLO WORLD',
        ]);

        $this->assertEquals('hello world', $model->name);
    }
}