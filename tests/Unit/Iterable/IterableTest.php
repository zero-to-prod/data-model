<?php

namespace Tests\Unit\Iterable;

use Tests\TestCase;
use Zerotoprod\DataModel\DataModel;

class IterableTest extends TestCase
{
    /**
     * @test
     * @dataProvider iterableDataProvider
     *
     * @see          DataModel
     */
    public function iterable(array $data): void
    {
        $BaseClass = BaseClass::from($data);

        $this->assertInstanceOf(MyIterable::class, $BaseClass->{BaseClass::iterable});
        $this->assertInstanceOf(MyIterable::class, $BaseClass->{BaseClass::Child}->{Child::iterable});
        $this->assertEquals(['a', 'b', 'c'], iterator_to_array($BaseClass->{BaseClass::iterable}));
        $this->assertEquals(['a', 'b', 'c'], iterator_to_array($BaseClass->{BaseClass::Child}->{Child::iterable}));
    }

    public function iterableDataProvider(): array
    {
        $iterable = new MyIterable(['a', 'b', 'c']);

        return [
            'iterable' => [
                'data' => [
                    BaseClass::iterable => $iterable,
                    BaseClass::Child => [
                        Child::iterable => $iterable,
                    ],
                ]
            ]
        ];
    }
}