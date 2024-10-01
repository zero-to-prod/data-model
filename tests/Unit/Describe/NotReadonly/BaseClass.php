<?php

namespace Tests\Unit\Describe\NotReadonly;

use DateTime;
use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

class BaseClass
{
    use DataModel;

    public const string_from_class = 'string_from_class';
    public const string_from_function = 'string_from_function';
    public const DateTime = 'DateTime';
    public const no_type = 'no_type';

    #[Describe(['cast' => [Parser::class, 'arbitrary']])]
    public string $string_from_class;

    #[Describe(['cast' => 'parse'])]
    public string $string_from_function;

    #[Describe(['cast' => [Parser::class, 'dateTime']])]
    public DateTime $DateTime;
    public string $fullName;
    public $no_type;

    #[Describe('fullName')]
    public static function fullName($value, $context): string
    {
        return $context['first_name'].' '.$context['last_name'];
    }
}