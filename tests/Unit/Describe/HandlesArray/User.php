<?php

namespace Tests\Unit\Describe\HandlesArray;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

class User
{
    use DataModel;

    public const url = 'url';
    #[Describe([
        'cast' => [self::class, 'isUrl'],
        'protocols' => ['http']
    ])]
    public ?string $url;

    public static function isUrl($value): string
    {
        return $value;
    }
}