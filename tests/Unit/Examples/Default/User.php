<?php

namespace Tests\Unit\Examples\Default;

use Tests\Unit\Examples\ExtendsTrait\DataModel;
use Zerotoprod\DataModel\Describe;

class User
{
    use DataModel;

    #[Describe(['default' => 'James'])]
    public string $name;
}