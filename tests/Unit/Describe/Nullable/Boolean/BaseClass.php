<?php

namespace Tests\Unit\Describe\Nullable\Boolean;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

class BaseClass
{
    use DataModel;

    public const true = 'true';
    public const false = 'false';

    #[Describe(['nullable'])]
    public ?string $true;

    #[Describe(['nullable' => false])]
    public string $false;
}