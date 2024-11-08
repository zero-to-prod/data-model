<?php

namespace Zerotoprod\DataModel;

use Attribute;

/**
 * Pass an associative array to the constructor to describe the behavior of a property when it is resolved.
 *
 * Property example:
 * ```
 * use Zerotoprod\DataModel\DataModel;
 * use Zerotoprod\DataModel\Describe;
 *
 * class User
 * {
 *     use DataModel;
 *
 *     #[Describe(['cast' => [__CLASS__, 'firstName'], 'required' => true])]
 *     public string $first_name;
 *
 *     #[Describe(['cast' => 'uppercase'])]
 *     public string $last_name;
 *
 *     #[Describe(['cast' => [__CLASS__, 'fullName']])]
 *     public string $full_name;
 *
 *
 *     public static function firstName(mixed $value, array $context): string
 *     {
 *         return strtoupper($value);
 *     }
 *
 *     public static function fullName(null $value, array $context): string
 *     {
 *         return "{$context['first_name']} {$context['last_name']}";
 *     }
 * }
 * ```
 * Method example:
 * ```
 * use Zerotoprod\DataModel\DataModel;
 * use Zerotoprod\DataModel\Describe;
 *
 * class User
 * {
 *     use DataModel;
 *
 *     public string $first_name;
 *     public string $last_name;
 *     public string $fullName;
 *
 *     #[Describe('last_name')]
 *     public function lastName(?string $value, array $context): string
 *     {
 *         return strtoupper($value);
 *     }
 *
 *     #[Describe('fullName')]
 *     public function fullName(null $value, array $context): string
 *     {
 *         return "{$context['first_name']} {$context['last_name']}";
 *     }
 * }
 * ```
 * Class example:
 * ```
 * use DateTimeImmutable;
 * use Zerotoprod\DataModel\DataModel;
 * use Zerotoprod\DataModel\Describe;
 *
 * function uppercase(mixed $value, array $context){
 *      return strtoupper($value);
 * }
 *
 * #[Describe([
 *  'cast' => [
 *      'string' => 'uppercase',
 *      DateTimeImmutable::class => [__CLASS__, 'toDateTimeImmutable'],
 *  ]
 * ])]
 * class User
 * {
 *     use DataModel;
 *
 *     public string $first_name;
 *     public DateTimeImmutable $registered;
 *
 *     public static function toDateTimeImmutable(string $value, array $context): DateTimeImmutable
 *     {
 *         return new DateTimeImmutable($value);
 *     }
 * }
 * ```
 *
 * @link https://github.com/zero-to-prod/data-model
 *
 * @see  https://github.com/zero-to-prod/data-model-helper
 * @see  https://github.com/zero-to-prod/data-model-factory
 * @see  https://github.com/zero-to-prod/transformable
 */
#[Attribute]
class Describe
{
    public const missing_as_null = 'missing_as_null';
    public const required = 'required';
    public const ignore = 'ignore';

    public string $from;

    public string|array $cast;

    public bool $required;

    public $default;

    public $pre;

    public $post;

    public bool $missing_as_null;

    public bool $ignore;

    /**
     *  Pass an associative array to the constructor to describe the behavior of a property when it is resolved.
     *
     *  Property example:
     *  ```
     *  use Zerotoprod\DataModel\DataModel;
     *  use Zerotoprod\DataModel\Describe;
     *
     *  class User
     *  {
     *      use DataModel;
     *
     *      #[Describe(['cast' => [__CLASS__, 'firstName'], 'function' => 'strtoupper'])]
     *      public string $first_name;
     *
     *      #[Describe(['cast' => 'uppercase'])]
     *      public string $last_name;
     *
     *      #[Describe(['cast' => [__CLASS__, 'fullName'], 'required' => true])]
     *      public string $full_name;
     *
     *      private static function firstName(mixed $value, array $context, ?\ReflectionAttribute $ReflectionAttribute, \ReflectionProperty $ReflectionProperty): string
     *      {
     *          return $ReflectionAttribute->getArguments()[0]['function']($value);
     *      }
     *
     *      public static function fullName(null $value, array $context): string
     *      {
     *          return "{$context['first_name']} {$context['last_name']}";
     *      }
     *  }
     *  ```
     *  Method example:
     *  ```
     *  use Zerotoprod\DataModel\DataModel;
     *  use Zerotoprod\DataModel\Describe;
     *
     *  class User
     *  {
     *      use DataModel;
     *
     *      public string $first_name;
     *      public string $last_name;
     *      public string $fullName;
     *
     *      #[Describe('last_name')]
     *      public function lastName(?string $value, array $context): string
     *      {
     *          return strtoupper($value);
     *      }
     *
     *      #[Describe('fullName')]
     *      public function fullName(null $value, array $context): string
     *      {
     *          return "{$context['first_name']} {$context['last_name']}";
     *      }
     *  }
     *  ```
     *  Class example:
     *  ```
     *  use DateTimeImmutable;
     *  use Zerotoprod\DataModel\DataModel;
     *  use Zerotoprod\DataModel\Describe;
     *
     *  function uppercase(mixed $value, array $context){
     *       return strtoupper($value);
     *  }
     *
     *  #[Describe([
     *   'cast' => [
     *       'string' => 'uppercase',
     *       DateTimeImmutable::class => [__CLASS__, 'toDateTimeImmutable'],
     *   ]
     *  ])]
     *  class User
     *  {
     *      use DataModel;
     *
     *      public string $first_name;
     *      public DateTimeImmutable $registered;
     *
     *      public static function toDateTimeImmutable(string $value, array $context): DateTimeImmutable
     *      {
     *          return new DateTimeImmutable($value);
     *      }
     *  }
     *  ```
     *
     * @param  string|array{'from': string,'pre': string|string[], 'cast': string|string[], 'post': string|string[], 'required': bool, 'default': mixed, 'missing_as_null': bool,
     *                                            'ignore': bool}|null  $attributes
     *
     * @link https://github.com/zero-to-prod/data-model
     *
     * @see  https://github.com/zero-to-prod/data-model-helper
     * @see  https://github.com/zero-to-prod/data-model-factory
     * @see  https://github.com/zero-to-prod/transformable
     */
    public function __construct(string|null|array $attributes = null)
    {
        if (is_countable($attributes)) {
            foreach ($attributes as $key => $value) {
                if (property_exists($this, $key)) {
                    if ($key === self::missing_as_null && !is_bool($value)) {
                        throw new InvalidValue(sprintf("Invalid value: `%s` should be a boolean.", self::missing_as_null));
                    }

                    if ($key === self::required && !is_bool($value)) {
                        throw new InvalidValue(sprintf("Invalid value: `%s` should be a boolean.", self::required));
                    }

                    if ($key === self::ignore && !is_bool($value)) {
                        throw new InvalidValue(sprintf("Invalid value: `%s` should be a boolean.", self::ignore));
                    }

                    $this->$key = $value;
                    continue;
                }
                if (is_string($value) && property_exists($this, $value) && $key === 0) {
                    if ($value === self::required) {
                        $this->$value = true;
                    }

                    if ($value === self::missing_as_null) {
                        $this->$value = true;
                    }

                    if ($value === self::ignore) {
                        $this->$value = true;
                    }
                }
            }
        }
    }
}