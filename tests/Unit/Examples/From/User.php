<?php

namespace Tests\Unit\Examples\From;

use Zerotoprod\DataModel\Describe;

class User
{
    use \Zerotoprod\DataModel\DataModel;

    public const first_name = 'first_name';

    #[Describe(['from' => 'firstName'])]
    public string $first_name;
}