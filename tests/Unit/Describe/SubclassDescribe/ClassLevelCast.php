<?php

namespace Tests\Unit\Describe\SubclassDescribe;

use Zerotoprod\DataModel\DataModel;

#[ChildDescribe(['cast' => ['string' => [self::class, 'upper']]])]
class ClassLevelCast
{
    use DataModel;

    public string $name;

    public static function upper(mixed $value, array $context): string
    {
        return strtoupper($value);
    }
}
