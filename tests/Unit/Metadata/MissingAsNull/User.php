<?php

namespace Tests\Unit\Metadata\MissingAsNull;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

#[Describe(['nullable' => true])]
class User
{
    use DataModel;

    public ?string $name;

    #[Describe(['nullable' => true])]
    public ?int $age;
}