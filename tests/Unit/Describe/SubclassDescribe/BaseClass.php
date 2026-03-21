<?php

namespace Tests\Unit\Describe\SubclassDescribe;

use Zerotoprod\DataModel\DataModel;

readonly class BaseClass
{
    use DataModel;

    #[ChildDescribe(['nullable' => true])]
    public ?string $nullable_prop;

    #[ChildDescribe(['default' => 'fallback'])]
    public string $default_prop;

    #[ChildDescribe(['assign' => 'fixed'])]
    public string $assign_prop;

    #[ChildDescribe(['ignore' => true])]
    public string $ignored_prop;
}
