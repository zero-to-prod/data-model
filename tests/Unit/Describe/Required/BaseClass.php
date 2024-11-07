<?php

namespace Tests\Unit\Describe\Required;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

class BaseClass
{
    use DataModel;

    public const required = 'required';
    public const required_no_value = 'required_no_value';

    #[Describe(['required' => true])]
    public string $required;

    #[Describe(['required'])]
    public string $required_no_value;
}