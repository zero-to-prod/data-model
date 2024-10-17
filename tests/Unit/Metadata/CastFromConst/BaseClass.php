<?php

namespace Tests\Unit\Metadata\CastFromConst;

use DateTimeImmutable;
use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

#[Describe(describe)]
class BaseClass
{
    use DataModel;

    public const DateTimeImmutable = 'DateTimeImmutable';
    public const name = 'name';

    public DateTimeImmutable $DateTimeImmutable;
    public string $name;
}