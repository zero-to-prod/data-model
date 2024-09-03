<?php

namespace Tests\Unit\FromStdClass;

class Child
{
    public const id = 'id';

    /* @var int $id */
    public $id;

    public function from(): self
    {
        $self = new self();
        $self->id = 1;

        return $self;
    }
}