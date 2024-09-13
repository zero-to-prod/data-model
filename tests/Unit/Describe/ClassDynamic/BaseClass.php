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

    #[Describe([
        'class' => [
            'name' => Parser::class,
            'method' => 'arbitrary',
            'include_context' => true,
        ]
    ])]
    public string $string;

    #[Describe(['class' => ['name' => Parser::class]])]
    public string $string2;

    #[Describe(['class' => ['method' => 'parse']])]
    public string $string3;

    #[Describe(['class' => Parser::class])]
    public string $string4;

    #[Describe([
        'class' => [
            'name' => Parser::class,
            'method' => 'arbitrary',
        ]
    ])]
    public string $string5;

    #[Describe([
        'class' => [
            'name' => Parser::class,
            'method' => 'dateTime',
        ]
    ])]
    public DateTime $DateTime;
}