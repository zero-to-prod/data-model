<?php

namespace Tests\Unit\Describe\Via;

use ReflectionAttribute;
use ReflectionProperty;
use RuntimeException;
use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

class BaseClass
{
    use DataModel;

    public const ChildClass = 'ChildClass';
    public const ChildClass2 = 'ChildClass2';

    #[Describe(['via' => 'via'])]
    public ChildClass $ChildClass;

    #[Describe(['via' => [ChildClass::class, 'via']])]
    public ChildClass $ChildClass2;
}