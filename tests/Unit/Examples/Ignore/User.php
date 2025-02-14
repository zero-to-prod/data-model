<?php

namespace Tests\Unit\Examples\Ignore;

use Tests\Unit\Examples\ExtendsTrait\DataModel;
use Zerotoprod\DataModel\Describe;

class User
{
    use DataModel;

    public string $name;

    #[Describe(['ignore' => true])]
    public int $age;

    #[Describe(['ignore'])]
    public int $height;
}