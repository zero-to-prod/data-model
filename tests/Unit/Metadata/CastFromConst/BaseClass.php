<?php

namespace Tests\Unit\Metadata\CastFromConst;

use DateTimeImmutable;
use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Metadata;

#[Metadata(metadata)]
readonly class BaseClass
{
    use DataModel;

    public const DateTimeImmutable = 'DateTimeImmutable';
    public const name = 'name';

    public DateTimeImmutable $DateTimeImmutable;
    public string $name;
}