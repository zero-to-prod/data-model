<?php

namespace Tests\Unit\Describe\MissingAsNull\Boolean;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

class BaseClass
{
    use DataModel;

    public const true = 'true';
    public const false = 'false';

    #[Describe(['missing_as_null'])]
    public ?string $true;

    #[Describe(['missing_as_null' => false])]
    public string $false;
}