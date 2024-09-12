<?php

namespace Tests\Unit\DataModel\FromClass;

class Child
{
    public const id = 'id';

    /* @var int $id */
    public $id;

    public static function from(): self
    {
        $self = new self();
        $self->id = 1;

        return $self;
    }
}