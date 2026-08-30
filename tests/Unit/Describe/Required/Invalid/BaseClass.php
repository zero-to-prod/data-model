<?php

namespace Tests\Unit\Describe\Required\Invalid;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

class BaseClass
{
    use DataModel;

    #[Describe(['required' => 'yes'])]
    public string $invalid;
}
