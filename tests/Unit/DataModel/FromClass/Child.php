<?php

namespace Tests\Unit\DataModel\FromClass;

readonly class Child
{
    public const id = 'id';
    public int $id;

    public static function from(): self
    {
        $self = new self();
        $self->id = 1;

        return $self;
    }
}