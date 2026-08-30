<?php

namespace Tests\Unit\Describe\Ignore\Invalid;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

class BaseClass
{
    use DataModel;

    #[Describe(['ignore' => 'yes'])]
    public string $invalid;
}
