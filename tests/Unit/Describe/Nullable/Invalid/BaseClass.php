<?php

namespace Tests\Unit\Describe\Nullable\Invalid;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

class BaseClass
{
    use DataModel;

    #[Describe(['nullable' => []])]
    public string $invalid;
}