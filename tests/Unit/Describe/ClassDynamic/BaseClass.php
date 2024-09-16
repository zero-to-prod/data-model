<?php

namespace Tests\Unit\Describe\ClassDynamic;

use DateTime;
use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

readonly class BaseClass
{
    use DataModel;

    public const string = 'string';
    public const string2 = 'string2';
    public const string3 = 'string3';
    public const string4 = 'string4';
    public const string5 = 'string5';
    public const DateTime = 'DateTime';
    public const null_value = 'null_value';
    public const function = 'function';

    #[Describe([
        'cast' => [
            'name' => Parser::class,
            'method' => 'arbitrary',
            'include_context' => true,
        ]
    ])]
    public string $string;

    #[Describe(['cast' => ['name' => Parser::class]])]
    public string $string2;

    #[Describe(['cast' => ['method' => 'parse']])]
    public string $string3;

    #[Describe(['cast' => Parser::class])]
    public string $string4;

    #[Describe([
        'cast' => [
            'name' => Parser::class,
            'method' => 'arbitrary',
        ]
    ])]
    public string $string5;

    #[Describe([
        'cast' => [
            'name' => Parser::class,
            'method' => 'dateTime',
        ]
    ])]
    public DateTime $DateTime;

    #[Describe([
        'cast' => [
            'name' => __CLASS__,
            'method' => 'fullName',
            'include_context' => true,
        ],
        'required' => true
    ])]
    public string $full_name;

    #[Describe([
        'cast' => [
            'name' => 'parse',
            'include_context' => true,
        ],
        'required' => true
    ])]
    public string $function;

    public static function fullName($value, $context): string
    {
        return $context['first_name'].' '.$context['last_name'];
    }
}