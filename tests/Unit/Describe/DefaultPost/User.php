<?php

namespace Tests\Unit\Describe\DefaultPost;

use RuntimeException;
use Tests\Unit\Examples\ExtendsTrait\DataModel;
use Zerotoprod\DataModel\Describe;

class User
{
    use DataModel;

    public string $age;

    #[Describe([
        'default' => 'James',
        'post' => [self::class, 'post']
    ])]
    public string $name;

    public static function post($value): string
    {
        throw new RuntimeException($value);
    }
}