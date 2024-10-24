<?php

namespace Tests\Unit\Metadata\MissingAsNullProperty;

use Zerotoprod\DataModel\Describe;

//#[Describe(['missing_as_null' => true])]
class User
{
    use \Zerotoprod\DataModel\DataModel;

    #[Describe(['missing_as_null' => true])]
    public ?string $name;

    #[Describe(['default' => 2])]
    public ?int $age;
}