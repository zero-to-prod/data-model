<?php

namespace Tests\Unit\Describe\SubclassDescribe;

use Zerotoprod\DataModel\DataModel;

#[ChildDescribe(['nullable' => true])]
readonly class ClassNullable
{
    use DataModel;

    public ?string $name;
    public ?int $age;
}
