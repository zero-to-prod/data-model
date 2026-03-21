<?php

namespace Tests\Unit\DataModel\FromString;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

#[Describe(['nullable' => true])]
class ClassNullable
{
    use DataModel;

    public ?string $name;
    public ?int $age;
}
