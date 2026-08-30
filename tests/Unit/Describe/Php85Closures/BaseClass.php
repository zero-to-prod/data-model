<?php

namespace Tests\Unit\Describe\Php85Closures;

use ReflectionAttribute;
use ReflectionProperty;
use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

/**
 * PHP 8.5 allows closures inside constant expressions, so a closure literal can be
 * written directly as an attribute argument (previously only strings, arrays, and
 * first-class callables were allowed there).
 *
 * @link https://github.com/zero-to-prod/data-model
 */
class BaseClass
{
    use DataModel;

    public const from_key = 'from_key';
    public const cast_prop = 'cast_prop';
    public const required_prop = 'required_prop';
    public const default_prop = 'default_prop';
    public const pre_prop = 'pre_prop';
    public const post_prop = 'post_prop';
    public const ignore_prop = 'ignore_prop';
    public const assign_prop = 'assign_prop';

    /** `from` resolved via a closure literal in the attribute. */
    #[Describe([
        Describe::from => static function (string $propertyName, array $context, ?ReflectionAttribute $Attribute, ReflectionProperty $Property): string {
            return 'renamed_key';
        },
    ])]
    public string $from_prop;

    /** `cast` resolved via a closure literal in the attribute. */
    #[Describe([
        Describe::cast => static function ($value): string {
            return strtoupper($value);
        },
    ])]
    public string $cast_prop;

    /** `required` resolved via a closure literal in the attribute. */
    #[Describe([
        Describe::required => static function (): bool {
            return true;
        },
    ])]
    public string $required_prop;

    /** `default` resolved via a closure literal in the attribute. */
    #[Describe([
        Describe::default => static function (): array {
            return self::defaultValue();
        },
    ])]
    public array $default_prop;

    /** `pre` and `post` hooks resolved via closure literals in the attribute. */
    #[Describe([
        Describe::pre => static function ($value, array $context, ?ReflectionAttribute $Attribute, ReflectionProperty $Property): void {
            BaseClass::$pre_calls[] = $value;
        },
        Describe::post => static function ($value, array $context, ?ReflectionAttribute $Attribute, ReflectionProperty $Property): void {
            BaseClass::$post_calls[] = $value;
        },
    ])]
    public string $hook_prop = 'default-hook-value';

    /** `ignore` resolved via a closure literal in the attribute. */
    #[Describe([
        Describe::ignore => static function (): bool {
            return true;
        },
    ])]
    public string $ignore_prop = 'untouched';

    /** `assign` resolved via a closure literal in the attribute. */
    #[Describe([
        Describe::assign => static function (): string {
            return 'assigned-value';
        },
    ])]
    public string $assign_prop;

    /** @var array<int, mixed> */
    public static array $pre_calls = [];
    /** @var array<int, mixed> */
    public static array $post_calls = [];

    public static function defaultValue(): array
    {
        return ['a', 'b', 'c'];
    }
}
