<?php

namespace Tests\Unit\Describe\SubclassDescribe;

use Zerotoprod\DataModel\DataModel;

#[ChildDescribe(['nullable' => true])]
class ClassNullable
{
    use DataModel;

    public ?string $name;
    public ?int $age;
}
