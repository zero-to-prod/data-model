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
    public string $from;
    public string|array $cast;
    public bool $required;
    public mixed $default;
    public mixed $pre;
    public mixed $post;
    public bool $missing_as_null;

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
     * @param  string|array{'from': string,'pre': string|string[], 'cast': string|string[], 'post': string|string[], 'required': bool, 'default': mixed, 'missing_as_null': bool}|null|  $attributes
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
                    $this->$key = $value;
                }
            }
        }
    }
}