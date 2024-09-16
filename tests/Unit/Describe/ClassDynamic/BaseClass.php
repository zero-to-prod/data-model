<?php

namespace Tests\Unit\Describe\ClassDynamic;

use DateTime;
use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

readonly class BaseClass
{
    use DataModel;

    public const string_from_class = 'string_from_class';
    public const string_from_function = 'string_from_function';
    public const DateTime = 'DateTime';

    #[Describe(['target' => [Parser::class, 'arbitrary']])]
    public string $string_from_class;

    #[Describe(['target' => 'parse'])]
    public string $string_from_function;

    #[Describe(['target' => [Parser::class, 'dateTime']])]
    public DateTime $DateTime;
    public string $fullName;

    public static function fullName($value, $context): string
    {
        return $context['first_name'].' '.$context['last_name'];
    }
}