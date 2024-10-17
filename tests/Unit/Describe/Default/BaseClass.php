<?php

namespace Tests\Unit\Describe\Default;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

class BaseClass
{
    use DataModel;

    public const string = 'string';

    #[Describe(['default' => '1'])]
    public string $string;
}