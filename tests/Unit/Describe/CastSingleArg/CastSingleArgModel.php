<?php

namespace Tests\Unit\Describe\CastSingleArg;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

class CastSingleArgModel
{
    use DataModel;

    #[Describe(['cast' => 'strtolower'])]
    public string $name;
}