<?php

namespace Tests\Unit\Describe\SubclassDescribe;

use Zerotoprod\DataModel\DataModel;

class RequiredClass
{
    use DataModel;

    #[ChildDescribe(['required' => true])]
    public string $required_prop;
}
