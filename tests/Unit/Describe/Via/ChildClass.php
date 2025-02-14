<?php

namespace Tests\Unit\Describe\Via;

class ChildClass
{
    public const int = 'int';

    public function __construct(public int $int)
    {
    }

    public static function via(array $context): self
    {
        return new self($context[self::int]);
    }
}