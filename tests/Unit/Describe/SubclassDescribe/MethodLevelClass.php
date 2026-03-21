<?php

namespace Tests\Unit\Describe\SubclassDescribe;

use Zerotoprod\DataModel\DataModel;

class MethodLevelClass
{
    use DataModel;

    public string $name;

    #[ChildDescribe('name')]
    public function resolveName(mixed $value, array $context, ?\ReflectionAttribute $Attribute, \ReflectionProperty $Property): string
    {
        return strtoupper($value);
    }
}
