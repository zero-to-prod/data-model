<?php

namespace Tests\Unit\Describe\MissingAsNull\Invalid;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

class BaseClass
{
    use DataModel;

    #[Describe(['missing_as_null' => []])]
    public string $invalid;
}