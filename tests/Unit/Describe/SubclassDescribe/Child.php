<?php

namespace Tests\Unit\Describe\SubclassDescribe;

class Child
{
    public function __construct(public readonly string $value) {}

    public static function create(array $context): self
    {
        return new self($context['value']);
    }
}
