<?php

namespace Tests\Unit\Metadata\MissingAsNullProperty;

use Zerotoprod\DataModel\Describe;

//#[Describe(['nullable' => true])]
class User
{
    use \Zerotoprod\DataModel\DataModel;

    #[Describe(['nullable' => true])]
    public ?string $name;

    #[Describe(['default' => ''])]
    public string $last_name;

    #[Describe(['default' => 2])]
    public ?int $age;
}