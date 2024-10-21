<?php

namespace Tests\Unit\Metadata\MissingAsNull;

use Zerotoprod\DataModel\Describe;

#[Describe(['missing_as_null' => true])]
class User
{
    use \Zerotoprod\DataModel\DataModel;

    public ?string $name;

    #[Describe(['missing_as_null' => true])]
    public ?int $age;
}