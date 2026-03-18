<?php

namespace Tests\Unit\Examples\Assign;

use Tests\Unit\Examples\ExtendsTrait\DataModel;
use Zerotoprod\DataModel\Describe;

class User
{
    use DataModel;

    #[Describe(['assign' => ['role' => 'admin']])]
    public array $config;
}
