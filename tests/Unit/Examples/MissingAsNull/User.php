<?php

namespace Tests\Unit\Examples\MissingAsNull;

use Zerotoprod\DataModel\Describe;

#[Describe(['nullable' => true])]
class User
{
    use \Zerotoprod\DataModel\DataModel;

    public string $name;
    public ?int $age;
}