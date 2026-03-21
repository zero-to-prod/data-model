<?php

namespace Tests\Unit\DataModel\FromString;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

class RequiredClass
{
    use DataModel;

    #[Describe(['required' => true])]
    public string $required_prop;
}
